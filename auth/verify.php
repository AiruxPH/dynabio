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

            <button type="submit" id="submitBtn" class="btn">
                <span id="btnText">Verify & Continue</span>
            </button>
        </form>

        <div class="auth-footer">
            Didn't receive the code? <br><a href="signup.php" style="margin-top: 5px; display: inline-block;">Try
                signing up again</a>
        </div>
    </div>

    <script>
        document.getElementById('verifyForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const code = document.getElementById('code').value;
            const submitBtn = document.getElementById('submitBtn');
            const alertBox = document.getElementById('alertBox');

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