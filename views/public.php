<!DOCTYPE html>
<html lang="en" <?php if (!$errorState)
    echo 'data-theme="' . htmlspecialchars($theme) . '"'; ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $errorState ? 'Not Found' : $fullName . ' - DynaBio'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css?v=2.0">
    <link rel="stylesheet" href="css/themes.css?v=1.0">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color, #050505);
            color: var(--text-primary, #f3f4f6);
            min-height: 100vh;
            padding: 3rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        .container-col {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            max-width: 650px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        /* Module Structure */
        .module-card {
            background: var(--card-bg, rgba(255, 255, 255, 0.03));
            border: 1px solid var(--card-border, rgba(255, 255, 255, 0.08));
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            box-sizing: border-box;
            box-shadow: var(--card-shadow, 0 8px 32px 0 rgba(0, 0, 0, 0.5));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Identity Basics */
        .identity-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color, #fff);
            box-shadow: 0 0 20px var(--primary-glow, rgba(255, 255, 255, 0.15));
            margin-bottom: 1.5rem;
        }

        .name {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            color: var(--primary-color, #fff);
        }

        .tagline {
            font-size: 1.1rem;
            color: var(--text-secondary, #a1a1aa);
            font-weight: 400;
            margin: 0.5rem 0 0 0;
        }

        .location {
            font-size: 0.9rem;
            color: var(--text-secondary, #a1a1aa);
            margin-top: 0.5rem;
        }

        .divider {
            width: 50px;
            height: 4px;
            background: var(--primary-color, #fff);
            border-radius: 2px;
            margin: 2rem auto;
            box-shadow: 0 0 10px var(--primary-glow);
        }

        .about {
            font-size: 1rem;
            line-height: 1.7;
            color: var(--text-primary);
            margin-bottom: 2rem;
        }

        /* Tags & Links */
        .skills-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 2.5rem;
        }

        .skill-badge {
            background: var(--tag-bg);
            color: var(--tag-text);
            border: 1px solid var(--tag-border);
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--primary-color, #fff);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            transform: translateY(-5px);
            background: var(--primary-color, #fff);
            color: var(--bg-color);
            box-shadow: 0 10px 20px var(--primary-glow);
        }

        /* Timeline Journey */
        .module-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0 0 2rem 0;
            text-align: center;
        }

        .timeline-wrapper {
            position: relative;
            padding-left: 2rem;
        }

        .timeline-wrapper::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 10px;
            bottom: 10px;
            width: 2px;
            background: var(--primary-color);
            opacity: 0.2;
            border-radius: 2px;
        }

        .t-item {
            position: relative;
            margin-bottom: 2.5rem;
        }

        .t-item:last-child {
            margin-bottom: 0;
        }

        .t-icon {
            position: absolute;
            left: -2rem;
            top: 0;
            width: 22px;
            height: 22px;
            transform: translateX(-40%);
            border-radius: 50%;
            background: var(--bg-color);
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            box-shadow: 0 0 10px var(--primary-glow);
        }

        .t-date {
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.25rem;
            display: block;
        }

        .t-title {
            font-size: 1.1rem;
            color: var(--primary-color);
            margin: 0 0 0.5rem 0;
            font-weight: 600;
        }

        .t-desc {
            font-size: 0.95rem;
            color: var(--text-primary);
            line-height: 1.6;
            margin: 0;
            opacity: 0.9;
        }

        /* GitHub Live Activity */
        .repo-card {
            display: block;
            text-decoration: none;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: transform 0.2s, border-color 0.2s;
        }

        .repo-card:hover {
            transform: translateX(5px);
            border-color: var(--primary-color);
        }

        .repo-title {
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
        }

        .repo-desc {
            margin: 0 0 0.75rem 0;
            color: var(--text-secondary);
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .repo-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .repo-meta span {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .footer-branding {
            text-align: center;
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.8rem;
            display: block;
            opacity: 0.6;
            transition: opacity 0.3s;
            margin-top: 1rem;
        }

        .footer-branding:hover {
            opacity: 1;
            color: var(--text-primary);
        }

        /* 404 State */
        .error-state {
            text-align: center;
        }

        .error-state i {
            font-size: 4rem;
            color: #ef4444;
            margin-bottom: 1rem;
        }

        .back-btn {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            z-index: 100;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .back-btn:hover {
            color: var(--primary-color, #fff);
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .back-btn {
                top: 1rem;
                left: 1rem;
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }
    </style>
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