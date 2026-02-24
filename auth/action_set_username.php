<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_utils.php';

if (!isset($_SESSION['setup_user_id']) && !isset($_SESSION['user_id'])) {
    jsonResponse(false, 'Session expired. Please login.', 'login.php');
}

$is_setup_flow = isset($_SESSION['setup_user_id']);
$user_id = $is_setup_flow ? $_SESSION['setup_user_id'] : $_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);

$skip = $data['skip'] ?? false;
$username = trim($data['username'] ?? '');

try {
    if ($skip) {
        // Generate formatting: "user_" + (000000 + user_id)
        $padded_id = str_pad($user_id, 6, "0", STR_PAD_LEFT);
        $generated_username = "user_" . $padded_id;

        $update = $conn->prepare("UPDATE users SET username = ? WHERE user_id = ?");
        $update->execute([$generated_username, $user_id]);

        if ($is_setup_flow) {
            unset($_SESSION['setup_user_id']);
            jsonResponse(true, 'Username auto-generated! Redirecting to login...', 'login.php');
        } else {
            jsonResponse(true, 'Username auto-generated!', null);
        }
    } else {
        if (empty($username)) {
            jsonResponse(false, 'Please enter a username or click skip.');
        }

        // Strict Backend Validation
        if ($username !== strtolower($username)) {
            jsonResponse(false, 'Username must be strictly lowercase.');
        }

        if (!preg_match('/^[a-zA-Z0-9](_(?!_)|[a-zA-Z0-9]){2,18}[a-zA-Z0-9]$/', $username)) {
            jsonResponse(false, 'Username must be 4-20 characters, alphanumeric or single underscores, and cannot start/end with an underscore.');
        }

        $reservedWords = ['admin', 'support', 'help', 'root', 'api', 'login', 'signup', 'settings', 'dashboard', 'system', 'staff', 'mod', 'owner', 'blog', 'about', 'contact', 'null', 'undefined'];
        if (in_array($username, $reservedWords)) {
            jsonResponse(false, 'This username is reserved and cannot be used.');
        }

        // Optional: Check if username already exists
        $check = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $check->execute([$username]);
        if ($check->fetch()) {
            jsonResponse(false, 'This username is already taken. Please choose another.');
        }

        $update = $conn->prepare("UPDATE users SET username = ? WHERE user_id = ?");
        $update->execute([$username, $user_id]);

        if ($is_setup_flow) {
            unset($_SESSION['setup_user_id']);
            jsonResponse(true, 'Username saved! Redirecting to login...', 'login.php');
        } else {
            jsonResponse(true, 'Username saved!', null);
        }
    }
} catch (Exception $e) {
    jsonResponse(false, 'Database error occurred: ' . $e->getMessage());
}
?>