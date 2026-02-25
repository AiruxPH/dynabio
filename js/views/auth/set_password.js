// Toggle Password Visibility
function setupToggle(btnId, inputId) {
    const btn = document.getElementById(btnId);
    if (!btn) return;

    btn.addEventListener('click', function () {
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

if (passwordInput) {
    passwordInput.addEventListener('input', function () {
        const val = this.value;
        if (strengthContainer) {
            if (val.length > 0) {
                strengthContainer.style.display = 'block';
            } else {
                strengthContainer.style.display = 'none';
            }
        }

        let metCount = 0;
        for (const key in reqs) {
            const req = reqs[key];
            if (!req.el) continue;

            const icon = req.el.querySelector('i');
            if (req.regex.test(val)) {
                if (icon) icon.className = 'fas fa-check req-met';
                metCount++;
            } else {
                if (icon) icon.className = 'fas fa-times req-unmet';
            }
        }

        isPasswordValid = metCount === 5;

        // Update bar
        const percentage = (metCount / 5) * 100;
        if (strengthFill) strengthFill.style.width = percentage + '%';

        if (metCount <= 2) {
            if (strengthFill) strengthFill.style.backgroundColor = '#ef4444'; // Red
            if (strengthText) {
                strengthText.textContent = 'Weak';
                strengthText.style.color = '#ef4444';
            }
        } else if (metCount <= 4) {
            if (strengthFill) strengthFill.style.backgroundColor = '#f59e0b'; // Yellow
            if (strengthText) {
                strengthText.textContent = 'Medium';
                strengthText.style.color = '#f59e0b';
            }
        } else {
            if (strengthFill) strengthFill.style.backgroundColor = '#22c55e'; // Green
            if (strengthText) {
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#22c55e';
            }
        }
    });
}

const passwordForm = document.getElementById('passwordForm');
if (passwordForm) {
    passwordForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!isPasswordValid) {
            showToast("Please meet all password requirements.", "danger");
            return;
        }

        const password = document.getElementById('password').value;
        const confirm_password = document.getElementById('confirm_password').value;
        const submitBtn = document.getElementById('submitBtn');

        if (password !== confirm_password) {
            showToast("Passwords do not match.", "danger");
            return;
        }

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Saving...';
        }

        try {
            const response = await fetch('action_set_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ password: password })
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, "success");

                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                showToast(data.message, "danger");
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span id="btnText">Save Password & Login</span>';
                }
            }
        } catch (error) {
            showToast("A network error occurred. Please try again.", "danger");
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span id="btnText">Save Password & Login</span>';
            }
        }
    });
}
