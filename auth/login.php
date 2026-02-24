<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Remove native eye icon */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none !important;
        }

        /* Fix autofill background/border */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px rgba(255, 255, 255, 0.05) inset !important;
            -webkit-text-fill-color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            transition: background-color 5000s ease-in-out 0s;
            border-radius: 8px;
        }

        /* Password wrapper */
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 40px;
            /* space for icon */
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            cursor: pointer;
            color: #94a3b8;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #cbd5e1;
        }
    </style>
</head>

<body>

    <div class="auth-container">
        <div class="auth-header">
            <h1>Welcome Back</h1>
            <p>Log in to your Dynabio account</p>
        </div>

        <div id="alertBox" class="alert" style="display: none;"></div>

        <form id="loginForm">
            <div class="form-group">
                <label for="email">Email Address or Username</label>
                <input type="text" id="email" class="form-control" placeholder="you@example.com or username" required
                    autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" class="form-control" placeholder="••••••••" required
                        autocomplete="new-password">
                    <i class="fas fa-eye-slash toggle-password" id="togglePasswordBtn"></i>
                </div>
            </div>

            <div class="form-group"
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <label style="display: flex; align-items: center; margin-bottom: 0; cursor: pointer;">
                    <input type="checkbox" id="remember"
                        style="margin-right: 8px; width: 16px; height: 16px; cursor: pointer; accent-color: #3b82f6;">
                    <span style="font-weight: 400; color: #cbd5e1;">Remember me</span>
                </label>
                <a href="forgot_password.php" style="font-size: 0.875rem; color: #60a5fa; text-decoration: none;">Forgot
                    Password?</a>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary">
                <span id="btnText">Log in</span>
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="signup.php">Create one</a>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');

        togglePasswordBtn.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;
            const submitBtn = document.getElementById('submitBtn');
            const alertBox = document.getElementById('alertBox');

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Authenticating...';
            alertBox.style.display = 'none';
            alertBox.className = 'alert';

            try {
                const response = await fetch('action_login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: email, password: password, remember: remember })
                });

                const data = await response.json();

                if (data.success) {
                    alertBox.textContent = data.message;
                    alertBox.classList.add('alert-success');
                    alertBox.style.display = 'block';

                    setTimeout(() => {
                        window.location.href = data.redirect || '../index.php'; // Default redirect
                    }, 1000);
                } else {
                    alertBox.textContent = data.message;
                    alertBox.classList.add('alert-danger');
                    alertBox.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span id="btnText">Log in</span>';

                    // Specific handling if redirection is needed (e.g. unverified)
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2500);
                    }
                }
            } catch (error) {
                alertBox.textContent = "A network error occurred. Please try again.";
                alertBox.classList.add('alert-danger');
                alertBox.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span id="btnText">Log in</span>';
            }
        });
    </script>
</body>

</html>