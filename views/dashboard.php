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

    <style>
        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .user-greeting {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .dashboard-profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .hub-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-view {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.4);
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-view:hover {
            background: rgba(59, 130, 246, 0.4);
            color: #fff;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
        }

        .btn-edit {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-edit:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* Theme Grid */
        .hub-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            margin-bottom: 2rem;
        }

        .theme-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .theme-option {
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .theme-option:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .theme-option.active {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.1);
        }

        .theme-option.active::after {
            content: '\f058';
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            top: 10px;
            right: 10px;
            color: #3b82f6;
            font-size: 1.25rem;
        }

        /* Specific Theme Previews */
        .color-preview {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-bottom: 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .theme-default .color-preview {
            background: linear-gradient(135deg, #ffffff, #a1a1aa);
        }

        .theme-neon .color-preview {
            background: linear-gradient(135deg, #0ff0fc, #ff007f);
            box-shadow: 0 0 15px rgba(15, 240, 252, 0.5);
        }

        .theme-midnight .color-preview {
            background: linear-gradient(135deg, #3b82f6, #1e293b);
        }

        .theme-minimal .color-preview {
            background: #f8fafc;
        }

        .theme-title {
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.25rem;
        }

        .theme-desc {
            font-size: 0.8rem;
            color: #a1a1aa;
            margin: 0;
        }
    </style>

    <div class="container" style="max-width: 1000px;">
        <div id="alertBox" class="alert" style="display: none; margin-bottom: 1.5rem;"></div>

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
        const themeCards = document.querySelectorAll('.theme-option');
        const alertBox = document.getElementById('alertBox');

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
                        alertBox.textContent = "Theme Updated Successfully!";
                        alertBox.className = 'alert alert-success';
                        alertBox.style.display = 'block';

                        // Auto-hide success
                        setTimeout(() => {
                            alertBox.style.display = 'none';
                        }, 3000);
                    } else {
                        // Revert on fail
                        this.classList.remove('active');
                        if (prevActive) prevActive.classList.add('active');

                        alertBox.textContent = data.message;
                        alertBox.className = 'alert alert-danger';
                        alertBox.style.display = 'block';
                    }
                } catch (e) {
                    this.classList.remove('active');
                    if (prevActive) prevActive.classList.add('active');
                    alertBox.textContent = "Network error while saving theme.";
                    alertBox.className = 'alert alert-danger';
                    alertBox.style.display = 'block';
                }
            });
        });
    </script>

    <?php include __DIR__ . '/includes/username_modal.php'; ?>
    <script src="js/background_animation.js"></script>
</body>

</html>