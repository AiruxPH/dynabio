<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($currentTheme); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dynabio</title>
    <link href="<?php echo ACTUAL_WEB_URL; ?>/css/font-google-inter.css" rel="stylesheet">
    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/font-awesome/ef9baa832e.js"></script>
    <link rel="stylesheet" href="style.css?v=3.0">
    <link rel="stylesheet" href="css/themes.css?v=3.0">
</head>

<body class="layout-dashboard" onresize="logResize()" onkeydown="handleKeyboardShortcut(event)"
    onscroll="handleScrollSpy()">

    <nav class="navbar">
        <div class="navbar-brand">Dynabio</div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <a href="about.php"
                style="color: #cbd5e1; text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: color 0.2s ease;">About
                Us</a>
            <?php if ($isSiteOwner): ?>
                <a href="themes.php"
                    style="color: #cbd5e1; text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: color 0.2s ease;">Themes</a>
            <?php endif; ?>
            <a href="user/profile.php"
                style="color: #cbd5e1; text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: color 0.2s ease;">My
                Profile</a>
            <a href="auth/logout.php" class="logout-btn">Log out</a>
        </div>
    </nav>

    <link rel="stylesheet" href="css/views/dashboard.css?v=3.0">
    <!-- Using public.css for the portfolio layout styles -->
    <link rel="stylesheet" href="css/views/public.css?v=2.0">
    <style>
        .layout-dashboard .container {
            max-width: 800px;
            padding: 0;
        }

        .action-bar {
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-bottom: 2rem;
            border-radius: 12px;
        }
    </style>

    <div class="container" onanimationend="logContainerReady()">

        <?php if ($isSiteOwner): ?>
            <div class="action-bar">
                <a href="user/editor.php" class="btn-edit" style="font-size: 0.9rem; padding: 0.5rem 1rem;"
                    onauxclick="detectMiddleClick(event)">
                    <i class="fas fa-pen-nib"></i> Edit Biodata
                </a>
            </div>
        <?php endif; ?>

        <?php if (isset($isOwnerEmpty) && $isOwnerEmpty): ?>
            <div
                style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.4); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; box-shadow: 0 0 20px rgba(59, 130, 246, 0.15);">
                <div>
                    <h3 style="margin: 0 0 0.5rem 0; color: #60a5fa; font-size: 1.25rem;"><i class="fas fa-magic"
                            style="margin-right: 8px;"></i>Welcome to DynaBio!</h3>
                    <p style="margin: 0; color: #cbd5e1; font-size: 0.95rem; line-height: 1.5;">It looks like the Site
                        Owner's
                        dynamic identity is empty. Check back later!</p>
                </div>
            </div>
        <?php else: ?>

            <div class="profile-layout" oncontextmenu="showCustomContext(event)">
                <!-- MODULE 1: IDENTITY -->
                <div class="module-card identity-wrapper" onmousemove="trailGlow(event, this)"
                    onmouseleave="clearTrailGlow(this)">
                    <div class="header">
                        <img src="<?php echo htmlspecialchars((string) ($photo ?? 'images/default.png')); ?>" alt="Avatar"
                            class="avatar" onmouseover="zoomProfile(this)" onmouseout="unzoomProfile(this)"
                            ondragstart="preventGhostDrag(event)" onload="avatarLoadGlow(this)"
                            onmousedown="startLongPress(this)" onmouseup="cancelLongPress()"
                            ontouchstart="startLongPress(this)" ontouchend="cancelLongPress()">
                        <h1 class="name" ondblclick="highlightWelcome(this)"><?php echo $fullName; ?></h1>
                        <?php if ($tagline): ?>
                            <h2 class="tagline" oncopy="notifyTaglineCopy()"><?php echo $tagline; ?></h2><?php endif; ?>
                        <?php if ($location): ?>
                            <div class="location" onclick="copyToClipboard('<?php echo addslashes($location); ?>', this)"
                                style="cursor: pointer;" title="Click to copy location"><i class="fas fa-map-marker-alt"></i>
                                <?php echo $location; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="divider" onmouseenter="pulseElement(this)" onclick="confettiPop(this)"></div>

                    <?php if ($aboutMe): ?>
                        <div class="about" onselectstart="logTextSelection()"><?php echo $aboutMe; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($skills)): ?>
                        <div class="skills-grid">
                            <?php foreach ($skills as $skill): ?>
                                <span class="skill-badge" onmouseenter="rippleBadge(this)"
                                    onclick="skillSpotlight(this)"><?php echo htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($socialLinks)): ?>
                        <div class="social-links">
                            <?php foreach ($socialLinks as $platform => $url): ?>
                                <?php $iconClass = isset($platformIcons[$platform]) ? $platformIcons[$platform] : 'fa-link'; ?>
                                <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="social-btn"
                                    aria-label="<?php echo ucfirst($platform); ?>"
                                    onclick="trackSocialClick('<?php echo $platform; ?>')" onfocus="glowSocialBtn(this)"
                                    onblur="unglowSocialBtn(this)">
                                    <i class="fa-brands <?php echo $iconClass; ?>"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- MODULE 1.5: STANDARD BIODATA FORM -->
                <div class="module-card" onmouseenter="peekBiodata(this)" onmouseleave="unpeekBiodata(this)">
                    <h2 class="module-title" onclick="toggleBiodataCollapse(this)"><i class="fas fa-address-card"></i>
                        Personal Biodata <i class="fas fa-chevron-up" id="biodataChevron"
                            style="font-size: 0.7rem; margin-left: 0.5rem; transition: transform 0.3s;"></i></h2>

                    <div class="glass-biodata-container">
                        <div class="glass-biodata-header">
                            <i class="fas fa-user-tie"></i> I. Personal Information
                        </div>

                        <div class="glass-biodata-row">
                            <div class="glass-biodata-label">Full Name</div>
                            <div class="glass-biodata-value"><strong><?php echo htmlspecialchars($fullName); ?></strong>
                            </div>
                        </div>

                        <?php if (!empty($profile['nickname'])): ?>
                            <div class="glass-biodata-row">
                                <div class="glass-biodata-label">Nickname</div>
                                <div class="glass-biodata-value"><?php echo htmlspecialchars($profile['nickname']); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($profile['position_desired'])): ?>
                            <div class="glass-biodata-row">
                                <div class="glass-biodata-label">Position Desired</div>
                                <div class="glass-biodata-value"><?php echo htmlspecialchars($profile['position_desired']); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="glass-biodata-row">
                            <div class="glass-biodata-label">Gender</div>
                            <div class="glass-biodata-value"><?php echo htmlspecialchars($profile['gender'] ?? '---'); ?>
                            </div>
                        </div>

                        <div class="glass-biodata-row">
                            <div class="glass-biodata-label">Date of Birth</div>
                            <div class="glass-biodata-value">
                                <?php if (!empty($profile['date_of_birth'])): ?>
                                    <?php
                                    $dob = new DateTime($profile['date_of_birth']);
                                    $now = new DateTime();
                                    $age = $now->diff($dob)->y;
                                    echo date('F j, Y', strtotime($profile['date_of_birth'])) . ' (Age ' . $age . ')';
                                    ?>
                                <?php else: ?>
                                    ---
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="glass-biodata-row">
                            <div class="glass-biodata-label">Civil Status</div>
                            <div class="glass-biodata-value">
                                <?php echo htmlspecialchars($profile['civil_status'] ?? '---'); ?>
                            </div>
                        </div>

                        <div class="glass-biodata-row">
                            <div class="glass-biodata-label">Citizenship</div>
                            <div class="glass-biodata-value">
                                <?php echo htmlspecialchars($profile['citizenship'] ?? '---'); ?>
                            </div>
                        </div>

                        <div class="glass-biodata-row">
                            <div class="glass-biodata-label">Religion</div>
                            <div class="glass-biodata-value"><?php echo htmlspecialchars($profile['religion'] ?? '---'); ?>
                            </div>
                        </div>

                        <div class="glass-biodata-row">
                            <div class="glass-biodata-label">Place of Birth</div>
                            <div class="glass-biodata-value">
                                <?php echo htmlspecialchars($profile['place_of_birth'] ?? '---'); ?>
                            </div>
                        </div>

                        <div class="glass-biodata-row">
                            <div class="glass-biodata-label">Height / Weight</div>
                            <div class="glass-biodata-value">
                                <?php echo htmlspecialchars($profile['height'] ?? '---'); ?> /
                                <?php echo htmlspecialchars($profile['weight'] ?? '---'); ?>
                            </div>
                        </div>

                        <div class="glass-biodata-row">
                            <div class="glass-biodata-label">Address</div>
                            <div class="glass-biodata-value">
                                <?php echo htmlspecialchars($profile['present_address'] ?? $profile['location'] ?? '---'); ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (!empty($profile['family_background'])):
                        $fb = json_decode($profile['family_background'], true);
                        if (is_array($fb) && (!empty($fb['spouse']) || !empty($fb['children']) || !empty($fb['parents']))):
                            ?>
                            <div class="glass-biodata-container" style="margin-top: 1.5rem;">
                                <div class="glass-biodata-header">
                                    <i class="fas fa-users"></i> II. Family Background
                                </div>
                                <?php if (!empty($fb['spouse'])): ?>
                                    <div class="glass-biodata-row">
                                        <div class="glass-biodata-label">Spouse</div>
                                        <div class="glass-biodata-value"><?php echo htmlspecialchars($fb['spouse']); ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($fb['children'])): ?>
                                    <div class="glass-biodata-row">
                                        <div class="glass-biodata-label">Children</div>
                                        <div class="glass-biodata-value"><?php echo htmlspecialchars($fb['children']); ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($fb['parents'])): ?>
                                    <div class="glass-biodata-row">
                                        <div class="glass-biodata-label">Parents</div>
                                        <div class="glass-biodata-value"><?php echo htmlspecialchars($fb['parents']); ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; endif; ?>


                </div>

                <!-- MODULE 2: GITHUB LIVE ACTIVITY -->
                <?php if ($githubUsername && $githubData && !isset($githubData['message'])): ?>
                    <div class="module-card">
                        <h2 class="module-title"><i class="fab fa-github"></i> Open Source Activity</h2>
                        <div>
                            <?php foreach ($githubData as $repo): ?>
                                <a href="<?php echo htmlspecialchars($repo['html_url']); ?>" target="_blank" class="repo-card">
                                    <h3 class="repo-title">
                                        <?php echo htmlspecialchars($repo['name']); ?>
                                        <i class="fas fa-arrow-right" style="opacity: 0.5; font-size: 0.8rem;"></i>
                                    </h3>
                                    <?php if (!empty($repo['description'])): ?>
                                        <p class="repo-desc"><?php echo htmlspecialchars($repo['description']); ?></p>
                                    <?php endif; ?>
                                    <div class="repo-meta">
                                        <?php if (!empty($repo['language'])): ?>
                                            <span><i class="fas fa-circle" style="color: var(--primary-color);"></i>
                                                <?php echo htmlspecialchars($repo['language']); ?></span>
                                        <?php endif; ?>
                                        <span><i class="fas fa-star"></i> <?php echo $repo['stargazers_count']; ?></span>
                                        <span><i class="fas fa-code-branch"></i> <?php echo $repo['forks_count']; ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- MODULE 3: THE JOURNEY (TIMELINE) -->
                <?php if (!empty($milestones)): ?>
                    <div class="module-card" onvisibilitychange="logTimelineView()">
                        <h2 class="module-title" ondblclick="reverseTimeline(this)"><i class="fas fa-route"></i> The Journey
                        </h2>
                        <div class="timeline-wrapper">
                            <?php foreach ($milestones as $ms): ?>
                                <div class="t-item">
                                    <div class="t-icon"><i class="<?php echo htmlspecialchars($ms['icon']); ?>"></i></div>
                                    <span class="t-date"><?php echo date("F j, Y", strtotime($ms['milestone_date'])); ?></span>
                                    <h3 class="t-title"><?php echo htmlspecialchars($ms['title']); ?></h3>
                                    <?php if (!empty($ms['description'])): ?>
                                        <p class="t-desc"><?php echo nl2br(htmlspecialchars($ms['description'])); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Scroll-to-Top Button -->
    <button id="scrollTopBtn" onclick="smoothScrollTop()" onmouseenter="spinScrollBtn(this)"
        onmouseleave="unspinScrollBtn(this)"
        style="display:none; position:fixed; bottom:2rem; right:2rem; width:48px; height:48px; border-radius:50%; border:1px solid rgba(255,255,255,0.15); background:rgba(0,0,0,0.6); color:#fff; cursor:pointer; font-size:1.2rem; z-index:999; transition: all 0.3s ease; backdrop-filter:blur(10px);">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        window.DashboardData = {
            currentTheme: <?php echo json_encode($currentTheme); ?>
        };
    </script>
    <script src="js/views/dashboard.js?v=4.0"></script>

    <?php include __DIR__ . '/../includes/username_modal.php'; ?>
    <script src="js/background_animation.js"></script>
    <script src="js/toast.js"></script>

    <!-- Inline Event Functions -->
    <script>
        /* ========== ORIGINAL INLINE FUNCTIONS ========== */
        function zoomProfile(element) {
            element.style.transform = "scale(1.1)";
            element.style.transition = "transform 0.3s ease";
        }
        function unzoomProfile(element) {
            element.style.transform = "scale(1)";
        }
        function highlightWelcome(element) {
            alert("Hello Dynabio! You double-clicked the name header.");
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

        /* ========== NEW INLINE FUNCTIONS ========== */

        // 1. onkeydown — Keyboard shortcut: Ctrl+E opens editor
        function handleKeyboardShortcut(e) {
            if (e.ctrlKey && e.key === 'e') {
                e.preventDefault();
                if (window.showToast) window.showToast('Opening editor via shortcut...', 'info');
                setTimeout(() => { window.location.href = 'user/editor.php'; }, 600);
            }
            if (e.key === 'Home') {
                smoothScrollTop();
            }
        }

        // 2. onscroll — Show/hide scroll-to-top button & scroll spy
        function handleScrollSpy() {
            const btn = document.getElementById('scrollTopBtn');
            if (btn) {
                btn.style.display = (window.scrollY > 300) ? 'flex' : 'none';
                btn.style.alignItems = 'center';
                btn.style.justifyContent = 'center';
            }
        }

        // 3. onclick — Smooth scroll to top
        function smoothScrollTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // 4. onmouseenter/onmouseleave — Spin the scroll-to-top button icon
        function spinScrollBtn(btn) {
            btn.style.transform = 'rotate(360deg) scale(1.15)';
            btn.style.borderColor = 'var(--primary-color)';
            btn.style.boxShadow = '0 0 15px var(--primary-glow, rgba(255,255,255,0.2))';
        }
        function unspinScrollBtn(btn) {
            btn.style.transform = 'rotate(0deg) scale(1)';
            btn.style.borderColor = 'rgba(255,255,255,0.15)';
            btn.style.boxShadow = 'none';
        }

        // 5. onload — Avatar glow pulse when image loads
        function avatarLoadGlow(img) {
            img.style.transition = 'box-shadow 0.5s ease';
            img.style.boxShadow = '0 0 30px var(--primary-color, #3b82f6), 0 0 60px var(--primary-glow, rgba(59,130,246,0.3))';
            setTimeout(() => {
                img.style.boxShadow = '0 0 20px var(--primary-glow, rgba(255,255,255,0.15))';
            }, 1500);
            console.log('Avatar loaded and glow pulse triggered.');
        }

        // 6. onmousedown/onmouseup — Long-press avatar to show full-size
        let longPressTimer = null;
        function startLongPress(img) {
            longPressTimer = setTimeout(() => {
                const overlay = document.createElement('div');
                overlay.id = 'avatarOverlay';
                overlay.onclick = function () { this.remove(); };
                overlay.style.cssText = 'position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.85);display:flex;align-items:center;justify-content:center;z-index:9999;cursor:zoom-out;animation:fadeIn 0.3s ease;';
                const bigImg = document.createElement('img');
                bigImg.src = img.src;
                bigImg.style.cssText = 'max-width:80vw;max-height:80vh;border-radius:16px;border:3px solid var(--primary-color,#fff);box-shadow:0 0 40px rgba(255,255,255,0.2);';
                overlay.appendChild(bigImg);
                document.body.appendChild(overlay);
                if (window.showToast) window.showToast('Long-press detected! Tap anywhere to close.', 'info');
            }, 700);
        }
        function cancelLongPress() {
            clearTimeout(longPressTimer);
        }

        // 7. onclick — Copy location to clipboard
        function copyToClipboard(text, el) {
            navigator.clipboard.writeText(text).then(() => {
                if (window.showToast) window.showToast('📍 Location copied to clipboard!', 'success');
                el.style.transition = 'color 0.3s';
                el.style.color = 'var(--primary-color)';
                setTimeout(() => { el.style.color = ''; }, 1500);
            });
        }

        // 8. onmouseenter — Divider pulse animation
        function pulseElement(el) {
            el.style.transition = 'all 0.5s ease';
            el.style.width = '120px';
            el.style.boxShadow = '0 0 20px var(--primary-color)';
            setTimeout(() => {
                el.style.width = '50px';
                el.style.boxShadow = '0 0 10px var(--primary-glow)';
            }, 800);
        }

        // 9. onclick — Mini confetti burst from divider
        function confettiPop(origin) {
            const colors = ['#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'];
            const rect = origin.getBoundingClientRect();
            for (let i = 0; i < 12; i++) {
                const dot = document.createElement('div');
                dot.style.cssText = `position:fixed;width:6px;height:6px;border-radius:50%;pointer-events:none;z-index:9999;background:${colors[i % colors.length]};left:${rect.left + rect.width / 2}px;top:${rect.top}px;transition:all 0.8s cubic-bezier(.25,.46,.45,.94);`;
                document.body.appendChild(dot);
                requestAnimationFrame(() => {
                    dot.style.left = (rect.left + rect.width / 2 + (Math.random() - 0.5) * 200) + 'px';
                    dot.style.top = (rect.top + (Math.random() - 0.5) * 150) + 'px';
                    dot.style.opacity = '0';
                    dot.style.transform = `scale(${Math.random() * 2})`;
                });
                setTimeout(() => dot.remove(), 900);
            }
        }

        // 10. onselectstart — Log text selection in about section
        function logTextSelection() {
            console.log('User is selecting text from the About section.');
        }

        // 11. onmouseenter — Ripple effect on skill badges
        function rippleBadge(badge) {
            badge.style.transition = 'all 0.3s ease';
            badge.style.transform = 'scale(1.12)';
            badge.style.boxShadow = '0 0 12px var(--primary-glow, rgba(59,130,246,0.4))';
            setTimeout(() => {
                badge.style.transform = 'scale(1)';
                badge.style.boxShadow = 'none';
            }, 400);
        }

        // 12. onclick — Skill spotlight: briefly highlights just that skill
        function skillSpotlight(badge) {
            const all = document.querySelectorAll('.skill-badge');
            all.forEach(b => { b.style.opacity = '0.3'; b.style.transition = 'opacity 0.3s'; });
            badge.style.opacity = '1';
            badge.style.border = '1px solid var(--primary-color)';
            setTimeout(() => {
                all.forEach(b => { b.style.opacity = '1'; });
                badge.style.border = '';
            }, 1200);
        }

        // 13. onclick — Track social link click
        function trackSocialClick(platform) {
            console.log(`Social link clicked: ${platform}`);
            if (window.showToast) window.showToast(`Opening ${platform.charAt(0).toUpperCase() + platform.slice(1)}...`, 'info');
        }

        // 14. onfocus/onblur — Glow social buttons on keyboard tab focus
        function glowSocialBtn(btn) {
            btn.style.boxShadow = '0 0 15px var(--primary-color)';
            btn.style.transform = 'translateY(-3px)';
        }
        function unglowSocialBtn(btn) {
            btn.style.boxShadow = '';
            btn.style.transform = '';
        }

        // 15. onmouseenter/onmouseleave — Subtle card tilt toward cursor (parallax)
        function trailGlow(e, card) {
            const rect = card.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width - 0.5) * 6;
            const y = ((e.clientY - rect.top) / rect.height - 0.5) * 6;
            card.style.transition = 'transform 0.1s ease-out';
            card.style.transform = `perspective(800px) rotateY(${x}deg) rotateX(${-y}deg)`;
        }
        function clearTrailGlow(card) {
            card.style.transition = 'transform 0.4s ease';
            card.style.transform = 'perspective(800px) rotateY(0deg) rotateX(0deg)';
        }

        // 16. onclick — Toggle biodata section collapse
        let biodataCollapsed = false;
        function toggleBiodataCollapse(titleEl) {
            const containers = titleEl.closest('.module-card').querySelectorAll('.glass-biodata-container');
            const chevron = document.getElementById('biodataChevron');
            biodataCollapsed = !biodataCollapsed;
            containers.forEach(c => {
                c.style.transition = 'all 0.4s ease';
                c.style.maxHeight = biodataCollapsed ? '0' : '2000px';
                c.style.overflow = 'hidden';
                c.style.opacity = biodataCollapsed ? '0' : '1';
                c.style.marginTop = biodataCollapsed ? '0' : '';
            });
            if (chevron) chevron.style.transform = biodataCollapsed ? 'rotate(180deg)' : 'rotate(0deg)';
        }

        // 17. onmouseenter/onmouseleave — Biodata card border glow
        function peekBiodata(card) {
            card.style.transition = 'border-color 0.4s ease';
            card.style.borderColor = 'var(--primary-color, rgba(59,130,246,0.5))';
        }
        function unpeekBiodata(card) {
            card.style.borderColor = 'var(--card-border, rgba(255,255,255,0.08))';
        }

        // 18. oncontextmenu — Custom context menu (prevent default, show toast)
        function showCustomContext(e) {
            e.preventDefault();
            if (window.showToast) window.showToast('🛡️ Right-click disabled on portfolio content.', 'warning');
        }

        // 19. ondblclick — Reverse timeline order
        function reverseTimeline(titleEl) {
            const wrapper = titleEl.closest('.module-card').querySelector('.timeline-wrapper');
            if (!wrapper) return;
            const items = Array.from(wrapper.children);
            items.reverse().forEach(item => wrapper.appendChild(item));
            if (window.showToast) window.showToast('⏳ Timeline order reversed!', 'info');
        }
    </script>
</body>

</html>