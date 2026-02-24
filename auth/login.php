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

        /* Account Chooser Styles */
        .account-chooser {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .account-card {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .account-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-2px);
        }

        .account-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .account-info {
            flex: 1;
            text-align: left;
        }

        .account-name {
            font-weight: 600;
            color: #f8fafc;
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }

        .account-remove {
            position: absolute;
            right: 1rem;
            color: #64748b;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .account-remove:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        /* Active Account Preview (When selected) */
        .active-account-preview {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(59, 130, 246, 0.05);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 12px;
            margin-bottom: 1.5rem;
            justify-content: center;
        }

        .active-account-preview img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #3b82f6;
        }

        .active-account-preview .name {
            font-weight: 600;
            color: #f8fafc;
        }

        .active-account-preview .change-btn {
            background: none;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #94a3b8;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.75rem;
            margin-left: auto;
            transition: all 0.2s ease;
        }

        .active-account-preview .change-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #f8fafc;
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

        <div class="auth-footer" id="authFooter">
            Don't have an account? <a href="signup.php">Create one</a>
        </div>
    </div>

    <script>
        // DOM Elements
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const loginForm = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const accountChooser = document.getElementById('accountChooser');
        const accountList = document.getElementById('accountList');
        const useAnotherAccountBtn = document.getElementById('useAnotherAccountBtn');
        const activeAccountPreview = document.getElementById('activeAccountPreview');
        const activeAccountImg = document.getElementById('activeAccountImg');
        const activeAccountName = document.getElementById('activeAccountName');
        const changeAccountBtn = document.getElementById('changeAccountBtn');
        const identifierGroup = document.getElementById('identifierGroup');
        const authFooter = document.getElementById('authFooter');

        // State
        let selectedUsername = null;

        // Toggle Password Visibility
        togglePasswordBtn.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Initialize Account Chooser
        function loadRecentLogins() {
            const stored = localStorage.getItem('recent_logins');
            let logins = [];
            if (stored) {
                try {
                    logins = JSON.parse(stored);
                } catch (e) {
                    logins = [];
                }
            }

            if (logins.length > 0) {
                accountChooser.style.display = 'block';
                loginForm.style.display = 'none';
                authFooter.style.display = 'none';

                accountList.innerHTML = '';
                logins.forEach(account => {
                    const card = document.createElement('div');
                    card.className = 'account-card';
                    card.innerHTML = `
                        <img src="../${account.avatar_url}" alt="${account.username}" class="account-avatar" onerror="this.src='../images/default.png'">
                        <div class="account-info">
                            <div class="account-name">${account.username}</div>
                        </div>
                        <button class="account-remove" title="Remove account" data-username="${account.username}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;

                    // Card click
                    card.addEventListener('click', (e) => {
                        // Ignore if clicked on remove button
                        if (e.target.closest('.account-remove')) return;
                        selectAccount(account);
                    });

                    // Remove click
                    const removeBtn = card.querySelector('.account-remove');
                    removeBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        if (confirm(`Are you sure you want to remove ${account.username} from this device?`)) {
                            removeRecentLogin(account.username);
                        }
                    });

                    accountList.appendChild(card);
                });
            } else {
                showStandardLogin();
            }
        }

        function selectAccount(account) {
            selectedUsername = account.username;
            emailInput.value = account.username; // Auto-fill internally

            // UI Transitions
            accountChooser.style.display = 'none';
            loginForm.style.display = 'block';
            authFooter.style.display = 'none'; // Keep hidden during quick-login

            identifierGroup.style.display = 'none'; // Hide normal email input
            activeAccountPreview.style.display = 'flex';

            activeAccountImg.src = '../' + account.avatar_url;
            activeAccountName.textContent = account.username;

            passwordInput.focus();
        }

        function showStandardLogin() {
            selectedUsername = null;
            emailInput.value = '';

            accountChooser.style.display = 'none';
            loginForm.style.display = 'block';
            authFooter.style.display = 'block';

            identifierGroup.style.display = 'block';
            activeAccountPreview.style.display = 'none';
        }

        function removeRecentLogin(username) {
            let stored = localStorage.getItem('recent_logins');
            if (stored) {
                let logins = JSON.parse(stored);
                logins = logins.filter(acc => acc.username !== username);
                localStorage.setItem('recent_logins', JSON.stringify(logins));
                loadRecentLogins();
            }
        }

        function saveRecentLogin(username, avatar_url) {
            let logins = [];
            const stored = localStorage.getItem('recent_logins');
            if (stored) {
                try { logins = JSON.parse(stored); } catch (e) { }
            }

            // Remove existing entry if it exists to update it and move to top
            logins = logins.filter(acc => acc.username !== username);

            logins.unshift({
                username: username,
                avatar_url: avatar_url,
                last_login: Date.now()
            });

            // Cap at 5 accounts
            if (logins.length > 5) {
                logins = logins.slice(0, 5);
            }

            localStorage.setItem('recent_logins', JSON.stringify(logins));
        }

        // Event Listeners for UI switching
        useAnotherAccountBtn.addEventListener('click', showStandardLogin);
        changeAccountBtn.addEventListener('click', () => {
            emailInput.value = '';
            passwordInput.value = '';
            loadRecentLogins();
        });

        // Initialize on load
        loadRecentLogins();

        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // If selectedUsername is set, we use that, otherwise we use whatever they typed
            const email = selectedUsername ? selectedUsername : emailInput.value;
            const password = passwordInput.value;
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

                    // Trigger Account Chooser Save Logic
                    if (remember && data.user) {
                        saveRecentLogin(data.user.username, data.user.photo);
                    }

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
    <script src="../js/form_guards.js"></script>
    <script>
        // We do not track dirty state for login to avoid annoying the user on account switch
        // but we still want the robust offline protection logic.
        const loginGuard = new FormGuard('loginForm', 'submitBtn', { trackDirty: false });
    </script>
</body>

</html>