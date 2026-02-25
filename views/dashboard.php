<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css?v=2.0">
</head>

<body class="layout-dashboard">

    <nav class="navbar">
        <div class="navbar-brand">Dynabio</div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <a href="user/profile.php"
                style="color: #cbd5e1; text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: color 0.2s ease;">My
                Profile</a>
            <a href="auth/logout.php" class="logout-btn">Log out</a>
        </div>
    </nav>

    <link rel="stylesheet" href="css/views/dashboard.css?v=2.0">

    <div class="container" style="max-width: 1000px;">

        <div class="dashboard-header">
            <div class="user-greeting">
                <img src="<?php echo htmlspecialchars($photo); ?>" alt="Profile" class="dashboard-profile-pic">
                <div>
                    <h1 style="margin: 0; font-size: 2rem;">Welcome,
                        <?php echo $displayName; ?>
                    </h1>
                    <p style="margin: 0.25rem 0 0 0; color: #a1a1aa;">Manage your dynamic biographical presence.</p>
                </div>
            </div>

            <div class="hub-actions">
                <a href="user/editor.php" class="btn-edit">
                    <i class="fas fa-pen-nib"></i> Edit Biodata
                </a>
                <?php if (!empty($user['username'])): ?>
                    <a href="view.php?u=<?php echo htmlspecialchars($user['username']); ?>" target="_blank"
                        class="btn-view">
                        <i class="fas fa-external-link-alt"></i> View Public Page
                    </a>
                <?php else: ?>
                    <button class="btn-view" style="opacity: 0.5; cursor: not-allowed;"
                        title="Set a username in your profile first">
                        <i class="fas fa-external-link-alt"></i> View Public Page
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="hub-card">
            <h2
                style="margin-top: 0; font-size: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.75rem;">
                <i class="fas fa-palette" style="color: #a1a1aa;"></i> Bio Appearance Theme
            </h2>
            <p style="color: #a1a1aa; font-size: 0.95rem; margin-bottom: 0;">Select a theme below to instantly transform
                how the world sees your public portfolio.</p>

            <div class="theme-grid">
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
            </div>
        </div>
    </div>

    <script>
        window.DashboardData = {
            // Minimal data required for now, expanding structure for future components
            currentTheme: "<?php echo htmlspecialchars($currentTheme); ?>"
        };
    </script>
    <script src="js/views/dashboard.js"></script>

    <?php include __DIR__ . '/includes/username_modal.php'; ?>
    <script src="js/background_animation.js"></script>
    <script src="js/toast.js"></script>
</body>

</html>