<?php
if (!defined('ACTUAL_WEB_URL')) {
    require_once __DIR__ . '/../../includes/config.php';
}
if (basename($_SERVER['PHP_SELF']) === 'forgot_password.php' && strpos($_SERVER['PHP_SELF'], '/views/auth/') !== false) {
    header("Location: " . ACTUAL_WEB_URL . "/auth/forgot_password.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Dynabio</title>
    <link href="<?php echo ACTUAL_WEB_URL; ?>/css/font-google-inter.css" rel="stylesheet">
    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/font-awesome/ef9baa832e.js"></script>
    <link rel="stylesheet" href="<?php echo ACTUAL_WEB_URL; ?>/style.css?v=2.0">
</head>

<body>

    <div class="auth-container">
        <div class="auth-header">
            <h1>Reset Password</h1>
            <p>Enter your email to receive a recovery code</p>
        </div>

        <form id="forgotForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" class="form-control" placeholder="Enter your email here..." required>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary">
                <span id="btnText">Send Recovery Code</span>
            </button>
        </form>

        <div class="auth-footer">
            <?php if ($isLoggedIn): ?>
                <a href="../user/profile.php">Back to Profile</a>
            <?php else: ?>
                Remembered your password? <a href="login.php">Log in</a>
            <?php endif; ?>
        </div>
    </div>

    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/toast.js"></script>
    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/views/auth/forgot_password.js"></script>
    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/background_animation.js"></script>
</body>

</html>