<?php
require_once __DIR__ . '/includes/db.php';

// Check if username is provided
if (!isset($_GET['u']) || empty(trim($_GET['u']))) {
    $errorState = true;
    $errorMessage = "No profile specified.";
} else {
    $requested_username = trim($_GET['u']);

    // Fetch user and biodata based on username
    $stmt = $pdo->prepare("
        SELECT u.photo, b.* 
        FROM users u
        LEFT JOIN biodata b ON u.user_id = b.user_id
        WHERE u.username = ?
    ");
    $stmt->execute([$requested_username]);
    $profile = $stmt->fetch();

    if (!$profile) {
        $errorState = true;
        $errorMessage = "Profile not found.";
    } else {
        $errorState = false;

        // Parse data
        $theme = !empty($profile['theme']) ? $profile['theme'] : 'default-glass';
        $fullName = !empty($profile['full_name']) ? htmlspecialchars($profile['full_name']) : htmlspecialchars($requested_username);
        $tagline = !empty($profile['tagline']) ? htmlspecialchars($profile['tagline']) : '';
        $aboutMe = !empty($profile['about_me']) ? nl2br(htmlspecialchars($profile['about_me'])) : '';
        $location = !empty($profile['location']) ? htmlspecialchars($profile['location']) : '';

        // Fix photo path
        $photo = !empty($profile['photo']) ? $profile['photo'] : 'user-placeholder.png';
        if ($photo !== 'user-placeholder.png' && strpos($photo, '../') === 0) {
            $photo = substr($photo, 3);
        }

        // Safely parse JSON
        $skills = [];
        if (!empty($profile['skills'])) {
            $parsed = json_decode($profile['skills'], true);
            if (is_array($parsed))
                $skills = $parsed;
        }

        $socialLinks = [];
        if (!empty($profile['social_links'])) {
            $parsed = json_decode($profile['social_links'], true);
            if (is_array($parsed))
                $socialLinks = $parsed;
        }

        // Map icons for platforms
        $platformIcons = [
            'twitter' => 'fa-x-twitter',
            'github' => 'fa-github',
            'linkedin' => 'fa-linkedin-in',
            'instagram' => 'fa-instagram',
            'youtube' => 'fa-youtube',
            'facebook' => 'fa-facebook-f',
            'website' => 'fa-globe'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en" <?php if (!$errorState)
    echo 'data-theme="' . htmlspecialchars($theme) . '"'; ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $errorState ? 'Not Found' : $fullName . ' - DynaBio'; ?>
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css?v=2.0">
    <link rel="stylesheet" href="css/themes.css?v=1.0">
    <style>
        body {
            /* Themes.css will override variables, but we setup layout here */
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color, #050505);
            color: var(--text-primary, #f3f4f6);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        .profile-card {
            background: var(--card-bg, rgba(255, 255, 255, 0.03));
            border: 1px solid var(--card-border, rgba(255, 255, 255, 0.08));
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 650px;
            box-shadow: var(--card-shadow, 0 8px 32px 0 rgba(0, 0, 0, 0.5));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: relative;
            z-index: 10;
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .divider {
            width: 50px;
            height: 4px;
            background: var(--primary-color, #fff);
            border-radius: 2px;
            margin: 2rem 0;
            box-shadow: 0 0 10px var(--primary-glow, rgba(255, 255, 255, 0.2));
        }

        .about {
            font-size: 1rem;
            line-height: 1.7;
            color: var(--text-primary, #f3f4f6);
            margin-bottom: 2rem;
            max-width: 500px;
        }

        .skills-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 2.5rem;
        }

        .skill-badge {
            background: var(--tag-bg, rgba(255, 255, 255, 0.05));
            color: var(--tag-text, #e4e4e7);
            border: 1px solid var(--tag-border, rgba(255, 255, 255, 0.1));
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
            background: var(--card-bg, rgba(255, 255, 255, 0.05));
            border: 1px solid var(--card-border, rgba(255, 255, 255, 0.1));
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
            color: var(--bg-color, #050505);
            box-shadow: 0 10px 20px var(--primary-glow, rgba(255, 255, 255, 0.2));
        }

        .footer-branding {
            text-decoration: none;
            color: var(--text-secondary, #a1a1aa);
            font-size: 0.8rem;
            margin-top: 3rem;
            opacity: 0.6;
            transition: opacity 0.3s;
        }

        .footer-branding:hover {
            opacity: 1;
            color: var(--text-primary, #fff);
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
    </style>
</head>

<body>

    <?php if ($errorState): ?>
        <div class="profile-card error-state">
            <i class="fas fa-exclamation-triangle"></i>
            <h1 class="name">404</h1>
            <p class="tagline">
                <?php echo $errorMessage; ?>
            </p>
            <a href="index.php" class="social-btn"
                style="width: auto; padding: 0 1.5rem; border-radius: 8px; font-size: 1rem; margin-top: 2rem;">
                Return Home
            </a>
        </div>
    <?php else: ?>
        <div class="profile-card">

            <img src="<?php echo htmlspecialchars($photo); ?>" alt="Profile avatar" class="avatar">

            <h1 class="name">
                <?php echo $fullName; ?>
            </h1>

            <?php if ($tagline): ?>
                <h2 class="tagline">
                    <?php echo $tagline; ?>
                </h2>
            <?php endif; ?>

            <?php if ($location): ?>
                <div class="location">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo $location; ?>
                </div>
            <?php endif; ?>

            <div class="divider"></div>

            <?php if ($aboutMe): ?>
                <div class="about">
                    <?php echo $aboutMe; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($skills)): ?>
                <div class="skills-grid">
                    <?php foreach ($skills as $skill): ?>
                        <span class="skill-badge">
                            <?php echo htmlspecialchars($skill); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($socialLinks)): ?>
                <div class="social-links">
                    <?php foreach ($socialLinks as $platform => $url): ?>
                        <?php
                        $iconClass = isset($platformIcons[$platform]) ? $platformIcons[$platform] : 'fa-link';
                        ?>
                        <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="social-btn"
                            aria-label="<?php echo ucfirst($platform); ?>">
                            <i class="fa-brands <?php echo $iconClass; ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <a href="index.php" class="footer-branding">Powered by DynaBio</a>
        </div>
    <?php endif; ?>

    <script src="js/background_animation.js"></script>
</body>

</html>