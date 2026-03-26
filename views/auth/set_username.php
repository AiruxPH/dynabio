<?php
if (!defined('ACTUAL_WEB_URL')) {
    require_once __DIR__ . '/../../includes/config.php';
}
if (basename($_SERVER['PHP_SELF']) === 'set_username.php' && strpos($_SERVER['PHP_SELF'], '/views/auth/') !== false) {
    header("Location: " . ACTUAL_WEB_URL . "/auth/set_username.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Username - Dynabio</title>
    <link href="<?php echo ACTUAL_WEB_URL; ?>/css/font-google-inter.css" rel="stylesheet">
    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/font-awesome/ef9baa832e.js"></script>
    <link rel="stylesheet" href="<?php echo ACTUAL_WEB_URL; ?>/style.css?v=2.0">
    <link rel="stylesheet" href="<?php echo ACTUAL_WEB_URL; ?>/css/views/auth/set_username.css?v=1.0">
</head>

<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>Choose Username</h1>
            <p>Customize how you appear on Dynabio</p>
        </div>
        <form id="usernameForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control" placeholder="Enter you username here..." required
                    autocomplete="off" oncut="preventUsernameCut(event)">
            </div>
            <button type="submit" id="submitBtn" class="btn btn-primary">
                <span id="btnText">Save Username</span>
            </button>
            <button type="button" id="skipBtn" class="btn skip-btn">
                Skip for now
            </button>
        </form>
    </div>
    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/toast.js"></script>
    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/views/auth/set_username.js"></script>
    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/background_animation.js"></script>
    <script>
        // Phase 8 Inline Events
        function preventUsernameCut(e) {
            e.preventDefault();
            if (window.showToast) window.showToast('Please do not cut your username text.', 'warning');
        }
    </script>
</body>

</html>