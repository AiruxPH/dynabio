const themeCards = document.querySelectorAll('.theme-option');

themeCards.forEach(card => {
    card.addEventListener('click', async function () {
        // Prevent duplicate saves if already active
        if (this.classList.contains('active')) return;

        const selectedTheme = this.getAttribute('data-theme-id');
        const prevActive = document.querySelector('.theme-option.active');

        // Optimistic UI update
        if (prevActive) prevActive.classList.remove('active');
        this.classList.add('active');

        // Fire AJAX
        try {
            const response = await fetch('user/action_change_theme.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ theme: selectedTheme })
            });

            const data = await response.json();

            if (data.success) {
                showToast("Theme Updated Successfully!", "success");
            } else {
                // Revert on fail
                this.classList.remove('active');
                if (prevActive) prevActive.classList.add('active');

                showToast(data.message, "danger");
            }
        } catch (e) {
            this.classList.remove('active');
            if (prevActive) prevActive.classList.add('active');
            showToast("Network error while saving theme.", "danger");
        }
    });
});
