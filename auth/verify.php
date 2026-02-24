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
    <link rel="stylesheet" href="../style.css">
    <style>
        .otp-fields {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .otp-fields input {
            width: 45px;
            height: 55px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.03);
            color: #f8fafc;
            transition: all 0.2s ease;
        }

        .otp-fields input:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            background: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body>

    <div class="auth-container">
        <div class="auth-header">
            <h1>Verify your Email</h1>
            <p>We sent a 6-character code to <strong>
                    <?php echo htmlspecialchars($email); ?>
                </strong></p>
        </div>

        <div id="alertBox" class="alert" style="display: none;"></div>

        <form id="verifyForm">
            <input type="hidden" id="email" value="<?php echo htmlspecialchars($email); ?>">

            <div class="form-group" style="text-align: center;">
                <label>Verification Code</label>
                <div class="otp-fields" id="otpFields">
                    <input type="text" maxlength="1" class="otp-input" autocomplete="off" autofocus>
                    <input type="text" maxlength="1" class="otp-input" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" autocomplete="off">
                </div>
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
                    // Clear all OTP fields
                    otpInputs.forEach(input => input.value = '');
                    otpInputs[0].focus();
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

        // OTP Field Logic
        const otpInputs = document.querySelectorAll('.otp-input');

        otpInputs.forEach((input, index) => {
            // Handle pasting a full code
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                // Get pasted text, remove non-alphanumeric characters
                let pastedData = e.clipboardData.getData('text').replace(/[^a-zA-Z0-9]/g, '');

                if (pastedData.length > 0) {
                    for (let i = 0; i < 6; i++) {
                        otpInputs[i].value = pastedData[i] || '';
                    }

                    // Focus the next empty box, or the last box if full
                    let nextEmptyIndex = Array.from(otpInputs).findIndex(input => !input.value);
                    if (nextEmptyIndex !== -1) {
                        otpInputs[nextEmptyIndex].focus();
                    } else {
                        otpInputs[5].focus();
                    }

                    // Auto-submit if fully pasted (all 6 digits present)
                    if (pastedData.length >= 6) {
                        document.getElementById('verifyForm').dispatchEvent(new Event('submit'));
                    }
                }
            });

            // Handle typing and auto-advancing
            input.addEventListener('input', (e) => {
                // Ensure only alphanumeric characters
                input.value = input.value.replace(/[^a-zA-Z0-9]/g, '');

                const val = input.value;
                if (val && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                } else if (val && index === otpInputs.length - 1) {
                    // Auto-submit on last digit typed
                    document.getElementById('verifyForm').dispatchEvent(new Event('submit'));
                }
            });

            // Handle keyboard navigation (Backspace, left/right arrows)
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    otpInputs[index - 1].focus();
                    // Optional: set cursor to end of string in the left box
                    setTimeout(() => otpInputs[index - 1].setSelectionRange(1, 1), 10);
                } else if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                    setTimeout(() => otpInputs[index + 1].setSelectionRange(1, 1), 10);
                }
            });

            // Prevent users from clicking out of order
            input.addEventListener('click', () => {
                // Find the very first empty box in the array
                let firstEmptyIndex = Array.from(otpInputs).findIndex(input => !input.value);

                // If there's an empty box AND they clicked a box that is further ahead than the first empty one
                if (firstEmptyIndex !== -1 && index > firstEmptyIndex) {
                    otpInputs[firstEmptyIndex].focus();
                } else if (input.value) {
                    // Set cursor to the right of the character if they clicked a filled box
                    input.setSelectionRange(1, 1);
                }
            });
        });

        // Verify Form Logic
        document.getElementById('verifyForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            // Gather the 6 digits
            let code = '';
            otpInputs.forEach(input => code += input.value);

            if (code.length !== 6) {
                alertBox.textContent = "Please enter all 6 digits.";
                alertBox.className = 'alert alert-danger';
                alertBox.style.display = 'block';
                return;
            }

            const submitBtn = document.getElementById('submitBtn');

            submitBtn.disabled = true;
            otpInputs.forEach(input => input.disabled = true);
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
                    otpInputs.forEach(input => input.disabled = false);
                    submitBtn.innerHTML = '<span id="btnText">Verify & Continue</span>';
                }
            } catch (error) {
                alertBox.textContent = "A network error occurred. Please try again.";
                alertBox.classList.add('alert-danger');
                alertBox.style.display = 'block';
                submitBtn.disabled = false;
                otpInputs.forEach(input => input.disabled = false);
                submitBtn.innerHTML = '<span id="btnText">Verify & Continue</span>';
            }
        });
    </script>
    <script src="../js/background_animation.js"></script>
</body>

</html>