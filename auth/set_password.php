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
    <link rel="stylesheet" href="style.css">
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
                <input type="password" id="password" class="form-control" placeholder="Minimum 8 characters" required
                    minlength="8">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" class="form-control" placeholder="Re-type password"
                    required minlength="8">
            </div>

            <button type="submit" id="submitBtn" class="btn">
                <span id="btnText">Save Password & Login</span>
            </button>
        </form>
    </div>

    <script>
        document.getElementById('passwordForm').addEventListener('submit', async function (e) {
            e.preventDefault();

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