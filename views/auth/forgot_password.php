<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css?v=2.0">
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

    <script src="../js/toast.js"></script>
    <script src="../../js/views/auth/forgot_password.js"></script>
    <script src="../js/background_animation.js"></script>
</body>

</html>