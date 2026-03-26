<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($currentTheme); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Themes - Dynabio</title>
    <link href="<?php echo ACTUAL_WEB_URL; ?>/css/font-google-inter.css" rel="stylesheet">
    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/font-awesome/ef9baa832e.js"></script>
    <link rel="stylesheet" href="style.css?v=3.0">
    <link rel="stylesheet" href="css/themes.css?v=3.0">
    <link rel="stylesheet" href="css/views/dashboard.css?v=3.0">
</head>

<body class="layout-dashboard">

    <nav class="navbar">
        <div class="navbar-brand">Dynabio</div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <a href="index.php"
                style="color: #cbd5e1; text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: color 0.2s ease;">Back
                to Dashboard</a>
        </div>
    </nav>

    <div class="container" style="max-width: 1000px;">
        <div class="hub-card">
            <h2
                style="margin-top: 0; font-size: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.75rem;">
                <i class="fas fa-palette" style="color: #a1a1aa;"></i> Bio Appearance Theme
            </h2>
            <p style="color: #a1a1aa; font-size: 0.95rem; margin-bottom: 0;">Select a theme below to instantly transform
                how the world sees your public portfolio.</p>

            <div class="theme-grid" onmousemove="tiltPreview(event, this)" onmouseout="resetTilt(this)">
                <!-- Glassmorphism -->
                <div class="theme-option theme-default <?php echo $currentTheme === 'default-glass' ? 'active' : ''; ?>"
                    data-theme-id="default-glass">
                    <div class="color-preview"></div>
                    <div class="theme-title">Default Glass</div>
                    <p class="theme-desc">Sleek, transparent dark aesthetics</p>
                </div>

                <!-- Neon Cyberpunk -->
                <div class="theme-option theme-neon <?php echo $currentTheme === 'neon-cyberpunk' ? 'active' : ''; ?>"
                    data-theme-id="neon-cyberpunk">
                    <div class="color-preview"></div>
                    <div class="theme-title">Neon Cyberpunk</div>
                    <p class="theme-desc">High contrast neon colors</p>
                </div>

                <!-- Midnight Blue -->
                <div class="theme-option theme-midnight <?php echo $currentTheme === 'midnight-blue' ? 'active' : ''; ?>"
                    data-theme-id="midnight-blue">
                    <div class="color-preview"></div>
                    <div class="theme-title">Midnight Blue</div>
                    <p class="theme-desc">Professional deep ocean hues</p>
                </div>

                <!-- Minimal Light -->
                <div class="theme-option theme-minimal <?php echo $currentTheme === 'minimal-light' ? 'active' : ''; ?>"
                    data-theme-id="minimal-light">
                    <div class="color-preview"></div>
                    <div class="theme-title">Minimal Light</div>
                    <p class="theme-desc">Clean and bright paper styling</p>
                </div>

                <!-- Solarized Amber -->
                <div class="theme-option theme-solarized <?php echo $currentTheme === 'solarized-amber' ? 'active' : ''; ?>"
                    data-theme-id="solarized-amber">
                    <div class="color-preview"></div>
                    <div class="theme-title">Solarized Amber</div>
                    <p class="theme-desc">Warm, nostalgic terminal vibes</p>
                </div>

                <!-- Emerald Matrix -->
                <div class="theme-option theme-emerald <?php echo $currentTheme === 'emerald-matrix' ? 'active' : ''; ?>"
                    data-theme-id="emerald-matrix">
                    <div class="color-preview"></div>
                    <div class="theme-title">Emerald Matrix</div>
                    <p class="theme-desc">Vibrant tech-focused green</p>
                </div>

                <!-- Rose Quartz -->
                <div class="theme-option theme-rose <?php echo $currentTheme === 'rose-quartz' ? 'active' : ''; ?>"
                    data-theme-id="rose-quartz">
                    <div class="color-preview"></div>
                    <div class="theme-title">Rose Quartz</div>
                    <p class="theme-desc">Soft, premium pink aesthetics</p>
                </div>

                <!-- Deep Space -->
                <div class="theme-option theme-space <?php echo $currentTheme === 'deep-space' ? 'active' : ''; ?>"
                    data-theme-id="deep-space">
                    <div class="color-preview"></div>
                    <div class="theme-title">Deep Space</div>
                    <p class="theme-desc">Dark, starry-night purple</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.DashboardData = {
            currentTheme: "<?php echo htmlspecialchars($currentTheme); ?>"
        };
    </script>
    <script src="js/views/dashboard.js?v=4.0"></script>
    <script src="js/background_animation.js"></script>
    <script src="js/toast.js"></script>
    <script>
        function tiltPreview(e, container) {
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const xOffset = (x / rect.width - 0.5) * 10;
            const yOffset = (y / rect.height - 0.5) * 10;
            container.style.transition = 'none';
            container.style.transform = `perspective(1000px) rotateY(${xOffset}deg) rotateX(${-yOffset}deg)`;
        }
        function resetTilt(container) {
            container.style.transition = 'transform 0.5s ease-out';
            container.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg)';
        }
    </script>
</body>

</html>