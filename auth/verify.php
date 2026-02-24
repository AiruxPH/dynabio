<?php
$email = $_GET['email'] ?? '';
if (empty($email)) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="auth-container">
        <div class="auth-header">
            <h1>Verify your Email</h1>
            <p>We sent a 16-character code to <strong>
                    <?php echo htmlspecialchars($email); ?>
                </strong></p>
        </div>

        <div id="alertBox" class="alert" style="display: none;"></div>

        <form id="verifyForm">
            <input type="hidden" id="email" value="<?php echo htmlspecialchars($email); ?>">

            <div class="form-group">
                <label for="code">Verification Code</label>
                <input type="text" id="code" class="form-control" placeholder="16-character code" required
                    minlength="16" maxlength="16" autocomplete="off" style="letter-spacing: 2px; text-align: center;">
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary">
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

    <script>
        // Timer Logic
        let timer = 60;
        const resendBtn = document.getElementById('resendBtn');
        const email = document.getElementById('email').value;
        const alertBox = document.getElementById('alertBox');

        function startTimer() {
            resendBtn.disabled = true;
            timer = 60;
            const interval = setInterval(() => {
                timer--;
                resendBtn.textContent = `Resend Code (${timer}s)`;
                if (timer <= 0) {
                    clearInterval(interval);
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Resend Code';
                    resendBtn.style.color = '#ffffff';
                }
            }, 1000);
        }

        // Start initial timer
        startTimer();

        resendBtn.addEventListener('click', async () => {
            if (timer > 0) return;

            resendBtn.disabled = true;
            resendBtn.innerHTML = '<span class="spinner"></span> Sending...';

            try {
                const response = await fetch('action_signup.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();

                if (data.success) {
                    alertBox.textContent = "New verification code sent!";
                    alertBox.className = 'alert alert-success';
                    alertBox.style.display = 'block';
                    startTimer();
                } else {
                    alertBox.textContent = data.message;
                    alertBox.className = 'alert alert-danger';
                    alertBox.style.display = 'block';
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Resend Code';
                }
            } catch (err) {
                alertBox.textContent = "Failed to resend. Check your connection.";
                alertBox.className = 'alert alert-danger';
                alertBox.style.display = 'block';
                resendBtn.disabled = false;
                resendBtn.textContent = 'Resend Code';
            }
        });

        // Verify Form Logic
        document.getElementById('verifyForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const code = document.getElementById('code').value;
            const submitBtn = document.getElementById('submitBtn');

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Verifying...';
            alertBox.style.display = 'none';
            alertBox.className = 'alert';

            try {
                const response = await fetch('action_verify.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: email, code: code })
                });

                const data = await response.json();

                if (data.success) {
                    alertBox.textContent = data.message;
                    alertBox.classList.add('alert-success');
                    alertBox.style.display = 'block';

                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    alertBox.textContent = data.message;
                    alertBox.classList.add('alert-danger');
                    alertBox.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span id="btnText">Verify & Continue</span>';
                }
            } catch (error) {
                alertBox.textContent = "A network error occurred. Please try again.";
                alertBox.classList.add('alert-danger');
                alertBox.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span id="btnText">Verify & Continue</span>';
            }
        });
    </script>
</body>

</html>