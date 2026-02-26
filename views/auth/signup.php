<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css?v=2.0">
</head>

<body>

    <div class="auth-container">
        <div class="auth-header">
            <h1>Create Account</h1>
            <p>Enter your email to get started</p>
        </div>

        <form id="signupForm" onsubmit="preventDoubleCheck(event, this)">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email here..."
                    required ondragenter="warnDragDrop()" oninvalid="customEmailInvalid(event)">
            </div>

            <button type="submit" id="submitBtn" class="btn">
                <span id="btnText">Continue with Email</span>
            </button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Log in</a>
        </div>
    </div>

    <script src="../../js/views/auth/signup.js"></script>
    <script src="../js/background_animation.js"></script>
    <script src="../js/toast.js"></script>
    <script>
        // Phase 8 Inline Events
        function warnDragDrop() {
            if (window.showToast) window.showToast('Please type your email explicitly instead of dragging.', 'warning');
        }
        function customEmailInvalid(e) {
            e.preventDefault();
            if (window.showToast) window.showToast('Please enter a valid email format containing an @ symbol.', 'error');
        }
        function preventDoubleCheck(e, form) {
            // Wait briefly to allow normal validation, then disable the button
            setTimeout(() => {
                const btn = form.querySelector('button[type="submit"]');
                if (btn && form.checkValidity()) {
                    btn.disabled = true;
                    btn.style.opacity = '0.7';
                }
            }, 50);
        }
    </script>
</body>

</html>