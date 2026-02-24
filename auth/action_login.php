<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_utils.php';

$data = json_decode(file_get_contents('php://input'), true);
$identifier = $data['email'] ?? '';
$password = $data['password'] ?? '';
$remember = $data['remember'] ?? false;

if (empty($identifier) || empty($password)) {
    jsonResponse(false, 'Email/Username and password are required.');
}

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        jsonResponse(false, 'Invalid email/username or password.');
    }

    // Check verification status and expiration rules
    if ($user['is_verified'] == 0) {
        // "if the user is not verified for more than 7 days, their account will be deleted"
        $deleted = performAccountExpirationChecks($conn, $user);
        if ($deleted) {
            jsonResponse(false, 'Your unverified account expired after 7 days and was deleted. Please sign up again.', 'signup.php');
        }

        // "if the user is not verified for more than 24 hours, they will be asked to sign up again" (New code sent)
        if (isCodeExpired($user['date_registered'])) {
            $code = generateVerificationCode();
            $update = $conn->prepare("UPDATE users SET verification_code = ?, date_registered = NOW() WHERE user_id = ?");
            $update->execute([$code, $user['user_id']]);
            sendVerificationEmail($user['email'], $code, 'signup');

            jsonResponse(false, 'Verification expired safely. A new code was sent to your email. Redirecting...', 'verify.php?email=' . urlencode($user['email']));
        }

        // < 24 hours, just not verified yet
        jsonResponse(false, 'Your account is not verified. Please check your email. Redirecting...', 'verify.php?email=' . urlencode($user['email']));
    }

    // Account is verified, check password
    if (!password_verify($password, $user['password'])) {
        jsonResponse(false, 'Invalid email/username or password.');
    }

    // Login successful
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    // If remember me is checked, extend the session cookie lifetime
    $params = session_get_cookie_params();
    if ($remember) {
        setcookie(session_name(), session_id(), time() + (86400 * 30), $params["path"], $params["domain"], $params["secure"], $params["httponly"]); // 30 days
    } else {
        setcookie(session_name(), session_id(), 0, $params["path"], $params["domain"], $params["secure"], $params["httponly"]); // 0 means session expires when browser closes
    }

    $response_data = [
        'success' => true,
        'message' => 'Login successful!',
        'redirect' => '../index.php',
        'user' => [
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'photo' => $user['photo']
        ]
    ];
    echo json_encode($response_data);
    exit;

} catch (Exception $e) {
    jsonResponse(false, 'Database error occurred: ' . $e->getMessage());
}
?>