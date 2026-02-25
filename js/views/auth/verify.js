// Timer Logic
let timer = 60;
const resendBtn = document.getElementById('resendBtn');
const emailEl = document.getElementById('email');
const email = emailEl ? emailEl.value : '';

function startTimer() {
    if (!resendBtn) return;
    resendBtn.disabled = true;
    timer = 60;
    const interval = setInterval(() => {
        timer--;
        resendBtn.textContent = `Resend Code (${timer}s)`;
        if (timer <= 0) {
            clearInterval(interval);
            resendBtn.disabled = false;
            resendBtn.textContent = 'Resend Code';
            resendBtn.style.color = '#ffffff';
        }
    }, 1000);
}

// Start initial timer
startTimer();

if (resendBtn) {
    resendBtn.addEventListener('click', async () => {
        if (timer > 0) return;

        resendBtn.disabled = true;
        resendBtn.innerHTML = '<span class="spinner"></span> Sending...';

        try {
            const response = await fetch('action_signup.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email })
            });

            const data = await response.json();

            if (data.success) {
                showToast("New verification code sent!", "success");
                // Clear all OTP fields
                otpInputs.forEach(input => input.value = '');
                if (otpInputs[0]) otpInputs[0].focus();
                startTimer();
            } else {
                showToast(data.message, "danger");
                resendBtn.disabled = false;
                resendBtn.textContent = 'Resend Code';
            }
        } catch (err) {
            showToast("Failed to resend. Check your connection.", "danger");
            resendBtn.disabled = false;
            resendBtn.textContent = 'Resend Code';
        }
    });
}

// OTP Field Logic
const otpInputs = document.querySelectorAll('.otp-input');

otpInputs.forEach((input, index) => {
    // Handle pasting a full code
    input.addEventListener('paste', (e) => {
        e.preventDefault();
        // Get pasted text, remove non-alphanumeric characters
        let pastedData = e.clipboardData.getData('text').replace(/[^a-zA-Z0-9]/g, '');

        if (pastedData.length > 0) {
            for (let i = 0; i < 6; i++) {
                if (otpInputs[i]) otpInputs[i].value = pastedData[i] || '';
            }

            // Focus the next empty box, or the last box if full
            let nextEmptyIndex = Array.from(otpInputs).findIndex(input => !input.value);
            if (nextEmptyIndex !== -1) {
                otpInputs[nextEmptyIndex].focus();
            } else if (otpInputs[5]) {
                otpInputs[5].focus();
            }

            // Auto-submit if fully pasted (all 6 digits present)
            if (pastedData.length >= 6) {
                const verifyForm = document.getElementById('verifyForm');
                if (verifyForm) verifyForm.dispatchEvent(new Event('submit'));
            }
        }
    });

    // Handle typing and auto-advancing
    input.addEventListener('input', (e) => {
        // Ensure only alphanumeric characters
        input.value = input.value.replace(/[^a-zA-Z0-9]/g, '');

        const val = input.value;
        if (val && index < otpInputs.length - 1) {
            otpInputs[index + 1].focus();
        } else if (val && index === otpInputs.length - 1) {
            // Auto-submit on last digit typed
            const verifyForm = document.getElementById('verifyForm');
            if (verifyForm) verifyForm.dispatchEvent(new Event('submit'));
        }
    });

    // Handle keyboard navigation (Backspace, left/right arrows)
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !e.target.value && index > 0) {
            otpInputs[index - 1].focus();
        } else if (e.key === 'ArrowLeft' && index > 0) {
            otpInputs[index - 1].focus();
            // Optional: set cursor to end of string in the left box
            setTimeout(() => otpInputs[index - 1].setSelectionRange(1, 1), 10);
        } else if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
            otpInputs[index + 1].focus();
            setTimeout(() => otpInputs[index + 1].setSelectionRange(1, 1), 10);
        }
    });

    // Prevent users from clicking out of order
    input.addEventListener('click', () => {
        // Find the very first empty box in the array
        let firstEmptyIndex = Array.from(otpInputs).findIndex(input => !input.value);

        // If there's an empty box AND they clicked a box that is further ahead than the first empty one
        if (firstEmptyIndex !== -1 && index > firstEmptyIndex) {
            otpInputs[firstEmptyIndex].focus();
        } else if (input.value) {
            // Set cursor to the right of the character if they clicked a filled box
            input.setSelectionRange(1, 1);
        }
    });
});

// Verify Form Logic
const verifyForm = document.getElementById('verifyForm');
if (verifyForm) {
    verifyForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Gather the 6 digits
        let code = '';
        otpInputs.forEach(input => code += input.value);

        if (code.length !== 6) {
            showToast("Please enter all 6 digits.", "danger");
            return;
        }

        const submitBtn = document.getElementById('submitBtn');

        if (submitBtn) submitBtn.disabled = true;
        otpInputs.forEach(input => input.disabled = true);
        if (submitBtn) submitBtn.innerHTML = '<span class="spinner"></span> Verifying...';

        try {
            const response = await fetch('action_verify.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email, code: code })
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, "success");

                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1000);
            } else {
                showToast(data.message, "danger");
                if (submitBtn) submitBtn.disabled = false;
                otpInputs.forEach(input => input.disabled = false);
                if (submitBtn) submitBtn.innerHTML = '<span id="btnText">Verify & Continue</span>';
            }
        } catch (error) {
            showToast("A network error occurred. Please try again.", "danger");
            if (submitBtn) submitBtn.disabled = false;
            otpInputs.forEach(input => input.disabled = false);
            if (submitBtn) submitBtn.innerHTML = '<span id="btnText">Verify & Continue</span>';
        }
    });
}
