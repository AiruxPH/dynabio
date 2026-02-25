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
const backToChooserBtn = document.getElementById('backToChooserBtn');

// State
let selectedUsername = null;
let globalLoginsCache = [];

// Toggle Password Visibility
if (togglePasswordBtn && passwordInput) {
    togglePasswordBtn.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
}

// Initialize Account Chooser
async function loadRecentLogins() {
    const stored = localStorage.getItem('recent_logins');
    let logins = [];
    if (stored) {
        try {
            logins = JSON.parse(stored);
        } catch (e) {
            logins = [];
        }
    }

    // Synchronize with backend dynamically
    if (logins.length > 0) {
        try {
            const response = await fetch('action_sync_accounts.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accounts: logins })
            });
            const data = await response.json();

            if (data.success) {
                logins = data.synced_accounts;
                localStorage.setItem('recent_logins', JSON.stringify(logins));
            }
        } catch (e) {
            console.error("Account sync failed", e);
        }
    }

    globalLoginsCache = logins; // Store for the standard login view

    if (logins.length > 0 && accountChooser && accountList) {
        accountChooser.style.display = 'block';
        if (loginForm) loginForm.style.display = 'none';
        if (authFooter) authFooter.style.display = 'none';
        if (backToChooserBtn) backToChooserBtn.style.display = 'none';

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
            if (removeBtn) {
                removeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (confirm(`Are you sure you want to remove ${account.username} from this device?`)) {
                        removeRecentLogin(account.username);
                    }
                });
            }

            accountList.appendChild(card);
        });
    } else {
        showStandardLogin();
    }
}

function selectAccount(account) {
    selectedUsername = account.username;
    if (emailInput) emailInput.value = account.username; // Auto-fill internally

    // UI Transitions
    if (accountChooser) accountChooser.style.display = 'none';
    if (loginForm) loginForm.style.display = 'block';
    if (authFooter) authFooter.style.display = 'none'; // Keep hidden during quick-login
    if (backToChooserBtn) backToChooserBtn.style.display = 'flex'; // Allow them to go back to the chooser

    if (identifierGroup) identifierGroup.style.display = 'none'; // Hide normal email input
    if (activeAccountPreview) activeAccountPreview.style.display = 'flex';

    if (activeAccountImg) activeAccountImg.src = '../' + account.avatar_url;
    if (activeAccountName) activeAccountName.textContent = account.username;

    if (passwordInput) passwordInput.focus();
}

function showStandardLogin() {
    selectedUsername = null;
    if (emailInput) emailInput.value = '';

    if (accountChooser) accountChooser.style.display = 'none';
    if (loginForm) loginForm.style.display = 'block';
    if (authFooter) authFooter.style.display = 'block';

    // Only show back button if there are accounts they can go back to
    if (backToChooserBtn) {
        if (globalLoginsCache.length > 0) {
            backToChooserBtn.style.display = 'flex';
        } else {
            backToChooserBtn.style.display = 'none';
        }
    }

    if (identifierGroup) identifierGroup.style.display = 'block';
    if (activeAccountPreview) activeAccountPreview.style.display = 'none';
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

function saveRecentLogin(user_id, username, avatar_url) {
    let logins = [];
    const stored = localStorage.getItem('recent_logins');
    if (stored) {
        try { logins = JSON.parse(stored); } catch (e) { }
    }

    // Remove existing entry if it exists to update it and move to top
    logins = logins.filter(acc => acc.username !== username && acc.user_id !== user_id);

    logins.unshift({
        user_id: user_id,
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
if (useAnotherAccountBtn) useAnotherAccountBtn.addEventListener('click', showStandardLogin);
if (changeAccountBtn) {
    changeAccountBtn.addEventListener('click', () => {
        if (emailInput) emailInput.value = '';
        if (passwordInput) passwordInput.value = '';
        loadRecentLogins();
    });
}
if (backToChooserBtn) {
    backToChooserBtn.addEventListener('click', () => {
        if (emailInput) emailInput.value = '';
        if (passwordInput) passwordInput.value = '';
        loadRecentLogins();
    });
}

// Initialize on load
document.addEventListener('DOMContentLoaded', loadRecentLogins);

if (loginForm) {
    loginForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        // If selectedUsername is set, we use that, otherwise we use whatever they typed
        const email = selectedUsername ? selectedUsername : (emailInput ? emailInput.value : '');
        const password = passwordInput ? passwordInput.value : '';
        const rememberEl = document.getElementById('remember');
        const remember = rememberEl ? rememberEl.checked : false;
        const submitBtn = document.getElementById('submitBtn');

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Authenticating...';
        }

        try {
            const response = await fetch('action_login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email, password: password, remember: remember })
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');

                // Trigger Account Chooser Save Logic
                if (remember && data.user) {
                    saveRecentLogin(data.user.user_id, data.user.username, data.user.photo);
                }

                setTimeout(() => {
                    window.location.href = data.redirect || '../index.php'; // Default redirect
                }, 1000);
            } else {
                showToast(data.message, 'danger');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span id="btnText">Log in</span>';
                }

                // Specific handling if redirection is needed (e.g. unverified)
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2500);
                }
            }
        } catch (error) {
            showToast("A network error occurred. Please try again.", 'danger');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span id="btnText">Log in</span>';
            }
        }
    });

    // We do not track dirty state for login to avoid annoying the user on account switch
    // but we still want the robust offline protection logic.
    if (typeof FormGuard !== 'undefined') {
        const loginGuard = new FormGuard('loginForm', 'submitBtn', { trackDirty: false });
    }
}
