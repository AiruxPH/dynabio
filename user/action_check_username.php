<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_utils.php';

if (!isset($_SESSION['user_id'])) {
    jsonResponse(false, 'Unauthorized access.');
}

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';

if (empty($username)) {
    jsonResponse(false, 'Username cannot be empty.');
}

// 1. Lowercase check
if ($username !== strtolower($username)) {
    jsonResponse(false, 'Username must be strictly lowercase.');
}

// 2. Regex and Length Check
if (!preg_match('/^[a-zA-Z0-9](_(?!_)|[a-zA-Z0-9]){2,18}[a-zA-Z0-9]$/', $username)) {
    jsonResponse(false, '4-20 characters, alphanumeric or single underscores, cannot start/end with underscore.');
}

// 3. Reserved Keywords
$reservedWords = ['admin', 'support', 'help', 'root', 'api', 'login', 'signup', 'settings', 'dashboard', 'system', 'staff', 'mod', 'owner', 'blog', 'about', 'contact', 'null', 'undefined'];
if (in_array($username, $reservedWords)) {
    jsonResponse(false, 'This username is reserved and cannot be used.');
}

// 4. Database Duplication Check
try {
    $check = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
    $check->execute([$username, $_SESSION['user_id']]);
    if ($check->fetch()) {
        jsonResponse(false, 'Username is already taken.');
    }

    // All checks passed
    jsonResponse(true, 'Username is available.');

} catch (Exception $e) {
    jsonResponse(false, 'Database error occurred.');
}
?>