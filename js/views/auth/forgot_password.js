const forgotForm = document.getElementById('forgotForm');

if (forgotForm) {
    forgotForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const submitBtn = document.getElementById('submitBtn');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> Sending...';

        try {
            const response = await fetch('action_forgot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email })
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, "success");

                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                showToast(data.message, "danger");
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span id="btnText">Send Recovery Code</span>';
            }
        } catch (error) {
            showToast("A network error occurred. Please try again.", "danger");
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span id="btnText">Send Recovery Code</span>';
        }
    });
}
