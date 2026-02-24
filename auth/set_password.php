<?php
session_start();
if (!isset($_SESSION['verified_email'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Remove native eye icon */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            display: none !important;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 40px;
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

        /* Password Strength */
        .password-strength-container {
            display: none;
            margin-top: 10px;
            font-size: 0.85rem;
        }

        .strength-bar {
            height: 4px;
            width: 100%;
            background-color: #334155;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }

        .req-list {
            list-style: none;
            padding: 0;
            margin: 10px 0 0 0;
            color: #94a3b8;
        }

        .req-list li {
            margin-bottom: 3px;
            display: flex;
            align-items: center;
        }

        .req-list li i {
            margin-right: 8px;
            font-size: 0.75rem;
            width: 14px;
            text-align: center;
        }

        .req-met {
            color: #22c55e;
        }

        .req-unmet {
            color: #ef4444;
        }
    </style>
</head>

<body>

    <div class="auth-container">
        <div class="auth-header">
            <h1>Secure Your Account</h1>
            <p>Set a password for your Dynabio account</p>
        </div>

        <div id="alertBox" class="alert" style="display: none;"></div>

        <form id="passwordForm">
            <div class="form-group">
                <label for="password">New Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" class="form-control" placeholder="Minimum 8 characters"
                        required minlength="8" autocomplete="off">
                    <i class="fas fa-eye-slash toggle-password" id="togglePasswordBtn"></i>
                </div>

                <div class="password-strength-container" id="strengthContainer">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Password Strength:</span>
                        <span id="strengthText" style="font-weight: 600;">Weak</span>
                    </div>
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <ul class="req-list">
                        <li id="req_length"><i class="fas fa-times req-unmet"></i> At least 8 characters</li>
                        <li id="req_upper"><i class="fas fa-times req-unmet"></i> One uppercase letter</li>
                        <li id="req_lower"><i class="fas fa-times req-unmet"></i> One lowercase letter</li>
                        <li id="req_number"><i class="fas fa-times req-unmet"></i> One number</li>
                        <li id="req_special"><i class="fas fa-times req-unmet"></i> One special character</li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm_password" class="form-control" placeholder="Re-type password"
                        required minlength="8" autocomplete="off">
                    <i class="fas fa-eye-slash toggle-password" id="toggleConfirmPasswordBtn"></i>
                </div>
            </div>

            <button type="submit" id="submitBtn" class="btn">
                <span id="btnText">Save Password & Login</span>
            </button>
        </form>
    </div>

    <script>
        // Toggle Password Visibility
        function setupToggle(btnId, inputId) {
            document.getElementById(btnId).addEventListener('click', function () {
                const input = document.getElementById(inputId);
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
        setupToggle('togglePasswordBtn', 'password');
        setupToggle('toggleConfirmPasswordBtn', 'confirm_password');

        // Password Strength
        const passwordInput = document.getElementById('password');
        const strengthContainer = document.getElementById('strengthContainer');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        const reqs = {
            length: { regex: /.{8,}/, el: document.getElementById('req_length') },
            upper: { regex: /[A-Z]/, el: document.getElementById('req_upper') },
            lower: { regex: /[a-z]/, el: document.getElementById('req_lower') },
            number: { regex: /[0-9]/, el: document.getElementById('req_number') },
            special: { regex: /[^A-Za-z0-9]/, el: document.getElementById('req_special') }
        };

        let isPasswordValid = false;

        passwordInput.addEventListener('input', function () {
            const val = this.value;
            if (val.length > 0) {
                strengthContainer.style.display = 'block';
            } else {
                strengthContainer.style.display = 'none';
            }

            let metCount = 0;
            for (const key in reqs) {
                const req = reqs[key];
                const icon = req.el.querySelector('i');
                if (req.regex.test(val)) {
                    icon.className = 'fas fa-check req-met';
                    metCount++;
                } else {
                    icon.className = 'fas fa-times req-unmet';
                }
            }

            isPasswordValid = metCount === 5;

            // Update bar
            const percentage = (metCount / 5) * 100;
            strengthFill.style.width = percentage + '%';

            if (metCount <= 2) {
                strengthFill.style.backgroundColor = '#ef4444'; // Red
                strengthText.textContent = 'Weak';
                strengthText.style.color = '#ef4444';
            } else if (metCount <= 4) {
                strengthFill.style.backgroundColor = '#f59e0b'; // Yellow
                strengthText.textContent = 'Medium';
                strengthText.style.color = '#f59e0b';
            } else {
                strengthFill.style.backgroundColor = '#22c55e'; // Green
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#22c55e';
            }
        });

        document.getElementById('passwordForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            if (!isPasswordValid) {
                const alertBox = document.getElementById('alertBox');
                alertBox.textContent = "Please meet all password requirements.";
                alertBox.className = 'alert alert-danger';
                alertBox.style.display = 'block';
                return;
            }

            const password = document.getElementById('password').value;
            const confirm_password = document.getElementById('confirm_password').value;
            const submitBtn = document.getElementById('submitBtn');
            const alertBox = document.getElementById('alertBox');

            if (password !== confirm_password) {
                alertBox.textContent = "Passwords do not match.";
                alertBox.classList.add('alert-danger');
                alertBox.style.display = 'block';
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Saving...';
            alertBox.style.display = 'none';
            alertBox.className = 'alert';

            try {
                const response = await fetch('action_set_password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ password: password })
                });

                const data = await response.json();

                if (data.success) {
                    alertBox.textContent = data.message;
                    alertBox.classList.add('alert-success');
                    alertBox.style.display = 'block';

                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    alertBox.textContent = data.message;
                    alertBox.classList.add('alert-danger');
                    alertBox.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span id="btnText">Save Password & Login</span>';
                }
            } catch (error) {
                alertBox.textContent = "A network error occurred. Please try again.";
                alertBox.classList.add('alert-danger');
                alertBox.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span id="btnText">Save Password & Login</span>';
            }
        });
    </script>
</body>

</html>