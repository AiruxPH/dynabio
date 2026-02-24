<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_utils.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(false, 'Invalid email address.');
}

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        jsonResponse(false, 'This email is not found or does not have an account affiliated with it.');
    }

    if ($user['is_verified'] == 0) {
        jsonResponse(false, 'This account is not verified. Please log in to trigger a new verification code.', 'login.php');
    }

    // Generate new code 
    $code = generateVerificationCode();

    // We update the verification_code, but keep is_verified = 1 because they are still a verified user, just resetting password.
    $update = $conn->prepare("UPDATE users SET verification_code = ? WHERE user_id = ?");
    $update->execute([$code, $user['user_id']]);

    // Send reset email
    if (sendVerificationEmail($email, $code, 'forgot_password')) {
        jsonResponse(true, 'A recovery code was sent! Please check your email.', 'verify.php?email=' . urlencode($email));
    } else {
        jsonResponse(false, 'Failed to send the recovery email. Please try again later.');
    }

} catch (Exception $e) {
    jsonResponse(false, 'Database error occurred: ' . $e->getMessage());
}
?>