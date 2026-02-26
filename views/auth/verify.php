<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css?v=2.0">
    <link rel="stylesheet" href="../../css/views/auth/verify.css?v=1.0">
</head>

<body>

    <div class="auth-container">
        <div class="auth-header">
            <h1>Verify your Email</h1>
            <p>We sent a 6-character code to <strong>
                    <?php echo htmlspecialchars((string) $email); ?>
                </strong></p>
        </div>

        <form id="verifyForm">
            <input type="hidden" id="email" value="<?php echo htmlspecialchars((string) $email); ?>">

            <div class="form-group" style="text-align: center;">
                <label>Verification Code</label>
                <div class="otp-fields" id="otpFields" onwheel="preventScrollWheel(event)">
                    <input type="text" maxlength="1" class="otp-input" autocomplete="off" autofocus>
                    <input type="text" maxlength="1" class="otp-input" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" autocomplete="off">
                </div>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary" ontouchstart="shrinkButton(this)">
                <span id="btnText">Verify & Continue</span>
            </button>
        </form>

        <div style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
            <button type="button" id="resendBtn" class="btn" style="background: rgba(255,255,255,0.05); color: #94a3b8;"
                disabled>
                Resend Code (60s)
            </button>
            <a href="signup.php" class="btn"
                style="text-align: center; background: rgba(255,255,255,0.05); color: #cbd5e1; text-decoration: none; border: 1px solid rgba(255,255,255,0.1);">
                Change Email Address
            </a>
        </div>

        <div class="auth-footer" style="margin-top: 1.5rem;">
            <a href="login.php"
                style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; text-decoration: none; color: #94a3b8;">
                <i class="fas fa-arrow-left"></i> Back to login
            </a>
        </div>
    </div>

    <script src="../js/toast.js"></script>
    <script src="../../js/views/auth/verify.js"></script>
    <script src="../js/background_animation.js"></script>
    <script>
        // Phase 8 Inline Events
        function preventScrollWheel(e) {
            e.preventDefault();
        }
        function shrinkButton(btn) {
            btn.style.transform = 'scale(0.97)';
            setTimeout(() => {
                btn.style.transform = 'scale(1)';
            }, 150);
        }
    </script>
</body>

</html>