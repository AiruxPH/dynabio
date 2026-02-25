const signupForm = document.getElementById('signupForm');

if (signupForm) {
    signupForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const submitBtn = document.getElementById('submitBtn');

        // UI Loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> Sending code...';

        try {
            const response = await fetch('action_signup.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email })
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, "success");

                // Redirect to verification view
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                showToast(data.message, "danger");
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span id="btnText">Continue with Email</span>';
            }
        } catch (error) {
            showToast("A network error occurred. Please try again.", "danger");
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span id="btnText">Continue with Email</span>';
            if (typeof signupGuard !== 'undefined') signupGuard.setDirty(true);
        }
    });

    if (typeof FormGuard !== 'undefined') {
        const signupGuard = new FormGuard('signupForm', 'submitBtn');
    }
}
