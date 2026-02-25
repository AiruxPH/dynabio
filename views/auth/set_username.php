<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Username - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css?v=2.0">
    <style>
        .skip-btn {
            background: transparent;
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 1rem;
        }

        .skip-btn:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
            box-shadow: none;
            border-color: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>Choose Username</h1>
            <p>Customize how you appear on Dynabio</p>
        </div>
        <div id="alertBox" class="alert" style="display: none;"></div>
        <form id="usernameForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control" placeholder="Enter you username here..." required
                    autocomplete="off">
            </div>
            <button type="submit" id="submitBtn" class="btn btn-primary">
                <span id="btnText">Save Username</span>
            </button>
            <button type="button" id="skipBtn" class="btn skip-btn">
                Skip for now
            </button>
        </form>
    </div>
    <script>
        async function submitUsername(username, isSkip) {
            const btn = isSkip ? document.getElementById('skipBtn') : document.getElementById('submitBtn');
            const alertBox = document.getElementById('alertBox');

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Processing...';
            alertBox.style.display = 'none';

            try {
                const response = await fetch('action_set_username.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: username, skip: isSkip })
                });
                const data = await response.json();

                if (data.success) {
                    alertBox.textContent = data.message;
                    alertBox.className = 'alert alert-success';
                    alertBox.style.display = 'block';
                    setTimeout(() => window.location.href = data.redirect, 1500);
                } else {
                    alertBox.textContent = data.message;
                    alertBox.className = 'alert alert-danger';
                    alertBox.style.display = 'block';
                    btn.disabled = false;
                    btn.innerHTML = isSkip ? 'Skip for now' : 'Save Username';
                }
            } catch (error) {
                alertBox.textContent = "A network error occurred. Please try again.";
                alertBox.className = 'alert alert-danger';
                alertBox.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = isSkip ? 'Skip for now' : 'Save Username';
            }
        }

        document.getElementById('usernameForm').addEventListener('submit', function (e) {
            e.preventDefault();
            submitUsername(document.getElementById('username').value, false);
        });

        document.getElementById('skipBtn').addEventListener('click', function () {
            submitUsername('', true);
        });
    </script>
    <script src="../js/background_animation.js"></script>
</body>

</html>