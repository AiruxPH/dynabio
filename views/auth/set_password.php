<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css?v=2.0">
    <link rel="stylesheet" href="../../css/views/auth/set_password.css?v=1.0">
</head>

<body>

    <div class="auth-container">
        <div class="auth-header">
            <h1>Secure Your Account</h1>
            <p>Set a password for your Dynabio account</p>
        </div>

        <form id="passwordForm">
            <div class="form-group">
                <label for="password">New Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" class="form-control" placeholder="Enter your password here..."
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
                    <input type="password" id="confirm_password" class="form-control"
                        placeholder="Re-type your password..." required minlength="8" autocomplete="off">
                    <i class="fas fa-eye-slash toggle-password" id="toggleConfirmPasswordBtn"></i>
                </div>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary">
                <span id="btnText">Save Password & Login</span>
            </button>
        </form>
    </div>

    <script src="../js/toast.js"></script>
    <script src="../../js/views/auth/set_password.js"></script>
    <script src="../js/background_animation.js"></script>
</body>

</html>