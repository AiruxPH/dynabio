<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_utils.php';

// Allow json payload
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(false, 'Invalid email address.');
}

try {
    // 1. Check if user already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $isNewAccount = true;

    if ($user) {
        if ($user['is_verified'] == 1) {
            jsonResponse(false, 'An account with this email already exists and is verified.', 'login.php');
        }

        // If not verified, check expirations
        $deleted = performAccountExpirationChecks($conn, $user);

        if (!$deleted) {
            $isNewAccount = false;
        }
    }

    $code = generateVerificationCode();

    if ($isNewAccount) {
        // Create new account with empty password initially
        $insert = $conn->prepare("INSERT INTO users (email, password, verification_code, date_registered, role, is_verified) VALUES (?, '', ?, NOW(), 'client', 0)");
        $insert->execute([$email, $code]);
    } else {
        // Update existing unverified account (they disconnected or code expired after 24h)
        $update = $conn->prepare("UPDATE users SET verification_code = ?, date_registered = NOW() WHERE user_id = ?");
        $update->execute([$code, $user['user_id']]);
    }

    // Send email
    if (sendVerificationEmail($email, $code, 'signup')) {
        jsonResponse(true, 'Verification code sent! Please check your email.', 'verify.php?email=' . urlencode($email));
    } else {
        jsonResponse(false, 'Failed to send the verification email. Please try again later.');
    }

} catch (Exception $e) {
    jsonResponse(false, 'Database error occurred: ' . $e->getMessage());
}
?>