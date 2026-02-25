const themeCards = document.querySelectorAll('.theme-option');
let originalHtmlTheme = window.DashboardData ? window.DashboardData.currentTheme : 'default-glass';

themeCards.forEach(card => {
    // Live Preview on Hover
    card.addEventListener('mouseenter', function () {
        const hoverTheme = this.getAttribute('data-theme-id');
        document.documentElement.setAttribute('data-theme', hoverTheme);
    });

    // Revert Preview on Mouse Leave
    card.addEventListener('mouseleave', function () {
        document.documentElement.setAttribute('data-theme', originalHtmlTheme);
    });

    card.addEventListener('click', async function () {
        // Prevent duplicate saves if already active
        if (this.classList.contains('active')) return;

        const selectedTheme = this.getAttribute('data-theme-id');
        const prevActive = document.querySelector('.theme-option.active');

        // Optimistic UI update
        if (prevActive) prevActive.classList.remove('active');
        this.classList.add('active');

        originalHtmlTheme = selectedTheme;
        document.documentElement.setAttribute('data-theme', selectedTheme);

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
                if (prevActive) {
                    prevActive.classList.add('active');
                    originalHtmlTheme = prevActive.getAttribute('data-theme-id');
                    document.documentElement.setAttribute('data-theme', originalHtmlTheme);
                }

                showToast(data.message, "danger");
            }
        } catch (e) {
            this.classList.remove('active');
            if (prevActive) {
                prevActive.classList.add('active');
                originalHtmlTheme = prevActive.getAttribute('data-theme-id');
                document.documentElement.setAttribute('data-theme', originalHtmlTheme);
            }
            showToast("Network error while saving theme.", "danger");
        }
    });
});
