<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mail_helper.php';

/**
 * Generates a 16-character alphanumeric verification code
 */
function generateVerificationCode()
{
    $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $code = '';
    for ($i = 0; $i < 16; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $code;
}

/**
 * Sends the verification email
 */
function sendVerificationEmail($email, $code, $type = 'signup')
{
    $subject = $type === 'signup' ? 'Verify your Dynabio Account' : 'Dynabio Password Reset Verification';
    $message = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
        <h2 style='color: #333;'>Dynabio Verification</h2>
        <p>Your verification code is:</p>
        <h1 style='color: #007BFF; letter-spacing: 2px;'>{$code}</h1>
        <p>Please enter this code on the website to continue.</p>
        <p style='color: #777; font-size: 12px;'>If you didn't request this, please ignore this email.</p>
    </div>
    ";

    return sendEmail($email, $subject, $message);
}

/**
 * Performs routine checks for a user account:
 * 1. Deletes account if unverified for > 7 days
 * 2. Checks if code needs regeneration (> 24 hours)
 * Returns true if account was deleted, false otherwise.
 */
function performAccountExpirationChecks($conn, $user)
{
    if ($user['is_verified'] == 1 || empty($user['date_registered'])) {
        return false;
    }

    $registeredDate = new DateTime($user['date_registered']);
    $now = new DateTime();
    $diff = $now->diff($registeredDate);

    // If > 7 days, delete account
    if ($diff->days >= 7) {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$user['user_id']]);
        return true;
    }

    return false;
}

/**
 * Checks if the user's verification code is older than 24 hours.
 * Returns true if older than 24 hours.
 */
function isCodeExpired($date_registered)
{
    if (empty($date_registered))
        return false;

    $registeredDate = new DateTime($date_registered);
    $now = new DateTime();
    $diff = $now->diff($registeredDate);

    return $diff->days >= 1;
}

/**
 * Standardizes JSON response for AJAX requests
 */
function jsonResponse($success, $message, $redirect_url = null)
{
    $response = ['success' => $success, 'message' => $message];
    if ($redirect_url) {
        $response['redirect'] = $redirect_url;
    }
    echo json_encode($response);
    exit;
}
?>