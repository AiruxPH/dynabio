<!DOCTYPE html>
<html lang="en" <?php if (!$errorState)
    echo 'data-theme="' . htmlspecialchars($theme) . '"'; ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $errorState ? 'Not Found' : $fullName . ' - DynaBio'; ?></title>
    <link href="<?php echo ACTUAL_WEB_URL; ?>/css/font-google-inter.css" rel="stylesheet">
    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/font-awesome/ef9baa832e.js"></script>
    <link rel="stylesheet" href="style.css?v=2.0">
    <link rel="stylesheet" href="css/themes.css?v=1.0">
    <link rel="stylesheet" href="<?php echo ACTUAL_WEB_URL; ?>/css/views/public.css?v=2.0">
</head>

<body onload="logGreeting()" oncopy="warnCopy()" oncontextmenu="protectContent(event)" onscroll="handlePublicScroll()">

    <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>

    <?php if ($errorState): ?>
        <div class="container-col">
            <div class="module-card error-state">
                <i class="fas fa-exclamation-triangle"></i>
                <h1 class="name">404</h1>
                <p class="tagline"><?php echo $errorMessage; ?></p>
                <a href="index.php" class="social-btn"
                    style="width: auto; padding: 0 1.5rem; border-radius: 8px; font-size: 1rem; margin: 2rem auto 0;">Return
                    Home</a>
            </div>
        </div>
    <?php else: ?>
        <div class="container-col">

            <?php if (isset($isNewUser) && $isNewUser): ?>
                <!-- EMPTY STATE MODULE -->
                <div class="module-card identity-wrapper" style="text-align: center; padding: 4rem 2rem;">
                    <div
                        style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.05); border: 2px dashed rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                        <i class="fas fa-ghost" style="font-size: 2.5rem; color: rgba(255,255,255,0.2);"></i>
                    </div>
                    <h1 class="name" style="font-size: 1.75rem; color: #f8fafc; margin-bottom: 0.5rem;">Profile Not Set Up</h1>
                    <p class="about" style="color: #94a3b8; margin-bottom: 0;">This user has registered an account with DynaBio,
                        but hasn't configured their public biography or timeline yet. Check back later!</p>
                </div>
            <?php else: ?>

                <!-- MODULE 1: IDENTITY -->
                <div class="module-card identity-wrapper">
                    <div class="header">
                        <img src="<?php echo htmlspecialchars((string) ($photo ?? 'images/default.png')); ?>" alt="Avatar"
                            class="avatar" onerror="fallbackImage(this)">
                        <h1 class="name"><?php echo $fullName; ?></h1>
                        <?php if ($tagline): ?>
                            <h2 class="tagline"><?php echo $tagline; ?></h2><?php endif; ?>
                        <?php if ($location): ?>
                            <div class="location"><i class="fas fa-map-marker-alt"></i> <?php echo $location; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="divider"></div>

                    <?php if ($aboutMe): ?>
                        <div class="about"><?php echo $aboutMe; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($skills)): ?>
                        <div class="skills-grid">
                            <?php foreach ($skills as $skill): ?>
                                <span class="skill-badge"><?php echo htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($socialLinks)): ?>
                        <div class="social-links">
                            <?php foreach ($socialLinks as $platform => $url): ?>
                                <?php $iconClass = isset($platformIcons[$platform]) ? $platformIcons[$platform] : 'fa-link'; ?>
                                <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="social-btn"
                                    aria-label="<?php echo ucfirst($platform); ?>">
                                    <i class="fa-brands <?php echo $iconClass; ?>"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- MODULE 1.5: STANDARD BIODATA FORM -->
                <div class="module-card">
                    <h2 class="module-title"><i class="fas fa-address-card"></i> Personal Biodata</h2>

                    <div class="glass-biodata-container">
                        <div class="glass-biodata-header">
                            <i class="fas fa-user-tie"></i> I. Personal Information
                        </div>

                        <div class="glass-biodata-row">
                            <div class="glass-biodata-label">Full Name</div>
                            <div class="glass-biodata-value"><strong><?php echo htmlspecialchars($fullName); ?></strong></div>
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
                                <div class="glass-biodata-value"><?php echo htmlspecialchars($profile['position_desired']); ?></div>
                            </div>
                        <?php endif; ?>

                        <div class="glass-biodata-row">
                            <div class="glass-biodata-label">Gender</div>
                            <div class="glass-biodata-value"><?php echo htmlspecialchars($profile['gender'] ?? '---'); ?></div>
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
                            <div class="glass-biodata-value"><?php echo htmlspecialchars($profile['civil_status'] ?? '---'); ?>
                            </div>
                        </div>

                        <div class="glass-biodata-row">
                            <div class="glass-biodata-label">Citizenship</div>
                            <div class="glass-biodata-value"><?php echo htmlspecialchars($profile['citizenship'] ?? '---'); ?>
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
                    <div class="module-card">
                        <h2 class="module-title"><i class="fas fa-route"></i> The Journey</h2>
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

            <?php endif; ?>

            <div style="text-align: center; margin-top: 2rem;">
                <a href="about.php" class="footer-branding" style="display: inline-block; margin-right: 15px;">About Us</a>
                <a href="index.php" class="footer-branding" style="display: inline-block;">Powered by DynaBio Engine</a>
            </div>
        </div>
    <?php endif; ?>

    <script src="js/background_animation.js"></script>

    <!-- Phase 8: Academic Inline Event Functions -->
    <script>
        function logGreeting() {
            console.log("Portfolio Successfully Loaded! Welcome to DynaBio Engine.");
        }
        function warnCopy() {
            alert("Please respect the author's intellectual property!");
        }
        function protectContent(e) {
            e.preventDefault();
            alert("Right-click is restricted on this portfolio for privacy reasons.");
        }
        function fallbackImage(img) {
            img.onerror = null;
            img.src = 'user-placeholder.png';
        }
        function handlePublicScroll() {
            const scrollPos = window.scrollY;
            const avatar = document.querySelector('.avatar');
            if (avatar) {
                avatar.style.transform = `rotate(${scrollPos / 5}deg)`;
            }
        }
    </script>
</body>

</html>