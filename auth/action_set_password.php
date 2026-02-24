<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_utils.php';

if (!isset($_SESSION['verified_email'])) {
    jsonResponse(false, 'Session expired. Please verify your email again.', 'login.php');
}

$email = $_SESSION['verified_email'];

$data = json_decode(file_get_contents('php://input'), true);
$password = $data['password'] ?? '';

if (strlen($password) < 8) {
    jsonResponse(false, 'Password must be at least 8 characters long.');
}

if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
    jsonResponse(false, 'Password does not meet the requirements.');
}

try {
    // Determine flow based on existing password
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        jsonResponse(false, 'User not found.', 'login.php');
    }

    $isSignupFlow = empty($user['password']);

    // Hash the new password
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Update password and mark as verified
    $update = $conn->prepare("UPDATE users SET password = ?, is_verified = 1 WHERE email = ?");
    $update->execute([$hash, $email]);

    // Clear the verification session
    unset($_SESSION['verified_email']);

    if ($isSignupFlow) {
        // "if the passwords match, the user will be registered and will be redirected to the login page"
        jsonResponse(true, 'Account registered successfully! Redirecting to login...', 'login.php');
    } else {
        // Forgot password flow: "if the new passwords match, the user will be logged in"
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $email;
        jsonResponse(true, 'Password updated successfully! Logging you in...', '../index.php'); // Adjust target URL if needed
    }

} catch (Exception $e) {
    jsonResponse(false, 'Database error occurred: ' . $e->getMessage());
}
?>