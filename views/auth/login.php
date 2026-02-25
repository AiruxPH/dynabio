<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css?v=2.0">
    <link rel="stylesheet" href="../../css/views/auth/login.css?v=1.0">
</head>

<body>

    <div class="auth-container">
        <div class="auth-header">
            <h1>Welcome Back</h1>
            <p>Log in to your Dynabio account</p>
        </div>

        <!-- Account Chooser Interface -->
        <div id="accountChooser" style="display: none;">
            <div class="account-chooser" id="accountList">
                <!-- Dynamically populated cards go here -->
            </div>
            <button id="useAnotherAccountBtn" class="btn"
                style="background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #cbd5e1;">
                <i class="fas fa-user-plus" style="margin-right: 8px;"></i> Use another account
            </button>
        </div>

        <!-- Standard Login Form -->
        <form id="loginForm">
            <!-- Selected Account Banner -->
            <div id="activeAccountPreview" class="active-account-preview" style="display: none;">
                <img src="" id="activeAccountImg" alt="Avatar">
                <span class="name" id="activeAccountName"></span>
                <button type="button" class="change-btn" id="changeAccountBtn">Change</button>
            </div>

            <div class="form-group" id="identifierGroup">
                <label for="email">Email Address or Username</label>
                <input type="text" id="email" class="form-control" placeholder="Enter your email or username..."
                    required autocomplete="username">
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


        <div class="auth-footer" id="authFooter">
            Don't have an account? <a href="signup.php">Create one</a>
        </div>

        <!-- Back to Chooser Button (Hidden by default) -->
        <button id="backToChooserBtn" class="btn"
            style="display: none; margin-top: 1rem; background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #cbd5e1;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Back to Accounts
        </button>
    </div>

    <script src="../js/toast.js"></script>
    <script src="../js/form_guards.js"></script>
    <script src="../../js/views/auth/login.js"></script>
    <script src="../js/background_animation.js"></script>
</body>

</html>