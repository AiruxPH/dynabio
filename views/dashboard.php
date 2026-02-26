<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($currentTheme); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css?v=3.0">
    <link rel="stylesheet" href="css/themes.css?v=3.0">
</head>

<body class="layout-dashboard" onresize="logResize()">

    <nav class="navbar">
        <div class="navbar-brand">Dynabio</div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <a href="about.php"
                style="color: #cbd5e1; text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: color 0.2s ease;">About
                Us</a>
            <a href="user/profile.php"
                style="color: #cbd5e1; text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: color 0.2s ease;">My
                Profile</a>
            <a href="auth/logout.php" class="logout-btn">Log out</a>
        </div>
    </nav>

    <link rel="stylesheet" href="css/views/dashboard.css?v=3.0">

    <div class="container" style="max-width: 1000px;" onanimationend="logContainerReady()">

        <div class="dashboard-header">
            <div class="user-greeting">
                <img src="<?php echo htmlspecialchars($photo); ?>" alt="Profile" class="dashboard-profile-pic"
                    onmouseover="zoomProfile(this)" onmouseout="unzoomProfile(this)"
                    ondragstart="preventGhostDrag(event)">
                <div>
                    <h1 style="margin: 0; font-size: 2rem;" ondblclick="highlightWelcome(this)">Welcome,
                        <?php echo $displayName; ?>
                    </h1>
                    <p class="dashboard-tagline" style="margin: 0.25rem 0 0 0; color: #a1a1aa;"
                        oncopy="notifyTaglineCopy()">Manage your dynamic biographical presence.</p>
                </div>
            </div>

            <div class="hub-actions">
                <a href="user/editor.php" class="btn-edit" onauxclick="detectMiddleClick(event)">
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

        <?php if (isset($isNewUser) && $isNewUser): ?>
            <div
                style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.4); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; box-shadow: 0 0 20px rgba(59, 130, 246, 0.15);">
                <div>
                    <h3 style="margin: 0 0 0.5rem 0; color: #60a5fa; font-size: 1.25rem;"><i class="fas fa-magic"
                            style="margin-right: 8px;"></i>Welcome to DynaBio!</h3>
                    <p style="margin: 0; color: #cbd5e1; font-size: 0.95rem; line-height: 1.5;">It looks like your dynamic
                        identity is empty. Let's set up your timeline, skills, and public preview to get your portfolio
                        ready for the world.</p>
                </div>
                <a href="user/editor.php"
                    style="white-space: nowrap; padding: 0.75rem 1.5rem; background: #3b82f6; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; transition: background 0.2s, transform 0.2s; display: inline-flex; align-items: center; gap: 0.5rem;"
                    onmouseover="this.style.background='#2563eb'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.background='#3b82f6'; this.style.transform='translateY(0)';">
                    <i class="fas fa-rocket"></i> Set up Biodata
                </a>
            </div>
        <?php endif; ?>

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
            // Minimal data required for now, expanding structure for future components
            currentTheme: "<?php echo htmlspecialchars($currentTheme); ?>"
        };
    </script>
    <script src="js/views/dashboard.js?v=4.0"></script>

    <?php include __DIR__ . '/includes/username_modal.php'; ?>
    <script src="js/background_animation.js"></script>
    <script src="js/toast.js"></script>

    <!-- Phase 8: Academic Inline Event Functions -->
    <script>
        function zoomProfile(element) {
            element.style.transform = "scale(1.1)";
            element.style.transition = "transform 0.3s ease";
        }
        function unzoomProfile(element) {
            element.style.transform = "scale(1)";
        }
        function highlightWelcome(element) {
            alert("Hello Dynabio! You double-clicked the welcome header.");
            element.style.color = "var(--primary-color)";
            element.style.textShadow = "0 0 10px var(--primary-color)";
        }
        function logResize() {
            console.log(`Viewport dynamically resized to: ${window.innerWidth}px x ${window.innerHeight}px`);
        }
        function logContainerReady() {
            console.log('Dashboard UI has finished rendering.');
        }
        function preventGhostDrag(e) {
            e.preventDefault();
        }
        function notifyTaglineCopy() {
            if (window.showToast) window.showToast('Tagline safely copied to clipboard!', 'success');
        }
        function detectMiddleClick(e) {
            if (e.button === 1) {
                console.log('Spawning editor in a background tab via middle-click.');
            }
        }
        function tiltPreview(e, container) {
            // Apply a subtle 3D tilt based on mouse coordinates over the grid
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const xOffset = (x / rect.width - 0.5) * 10;
            const yOffset = (y / rect.height - 0.5) * 10;
            container.style.transition = 'none'; // Disable transition for instant tracking
            container.style.transform = `perspective(1000px) rotateY(${xOffset}deg) rotateX(${-yOffset}deg)`;
        }
        function resetTilt(container) {
            container.style.transition = 'transform 0.5s ease-out'; // Re-enable smooth transition back to center
            container.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg)';
        }
    </script>
</body>

</html>