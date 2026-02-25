async function submitUsername(username, isSkip) {
    const btn = isSkip ? document.getElementById('skipBtn') : document.getElementById('submitBtn');

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Processing...';
    }

    try {
        const response = await fetch('action_set_username.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username: username, skip: isSkip })
        });
        const data = await response.json();

        if (data.success) {
            showToast(data.message, "success");
            setTimeout(() => window.location.href = data.redirect, 1500);
        } else {
            showToast(data.message, "danger");
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = isSkip ? 'Skip for now' : 'Save Username';
            }
        }
    } catch (error) {
        showToast("A network error occurred. Please try again.", "danger");
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = isSkip ? 'Skip for now' : 'Save Username';
        }
    }
}

const usernameForm = document.getElementById('usernameForm');
if (usernameForm) {
    usernameForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const usernameInput = document.getElementById('username');
        if (usernameInput) submitUsername(usernameInput.value, false);
    });
}

const skipBtn = document.getElementById('skipBtn');
if (skipBtn) {
    skipBtn.addEventListener('click', function () {
        submitUsername('', true);
    });
}
