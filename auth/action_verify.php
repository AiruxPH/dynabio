<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_utils.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$code = $data['code'] ?? '';

if (empty($email) || empty($code) || strlen($code) !== 16) {
    jsonResponse(false, 'Invalid verification code format.');
}

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND verification_code = ?");
    $stmt->execute([$email, $code]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        jsonResponse(false, 'Incorrect verification code. Please try again.');
    }

    // Check if code is expired (> 24 hours) for unverified accounts
    if ($user['is_verified'] == 0 && isCodeExpired($user['date_registered'])) {
        jsonResponse(false, 'This verification code has expired (older than 24 hours). Please sign up again.', 'signup.php');
    }

    // Code is valid. Mark them in session so they can set password
    $_SESSION['verified_email'] = $user['email'];

    // We do NOT mark is_verified = 1 yet, that happens after they set the password
    // (As per structure: "proceed to add a password -> registered")

    jsonResponse(true, 'Code verified successfully! Redirecting...', 'set_password.php');

} catch (Exception $e) {
    jsonResponse(false, 'Database error occurred: ' . $e->getMessage());
}
?>