<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Dynabio</title>
    <link href="<?php echo ACTUAL_WEB_URL; ?>/css/font-google-inter.css" rel="stylesheet">
    <script src="<?php echo ACTUAL_WEB_URL; ?>/js/font-awesome/ef9baa832e.js"></script>
    <link rel="stylesheet" href="style.css?v=3.0">
    <link rel="stylesheet" href="css/views/about.css?v=1.0">
</head>

<body>

    <div class="about-container">

        <div class="about-header">
            <h1>About DynaBio</h1>
            <p>The next generation of dynamic personal portfolios.</p>
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: center;">
                <a href="https://dynabio.ccsblock2.com" target="_blank"
                    style="padding: 0.6rem 1.2rem; background: #3b82f6; color: white; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 0.9rem; transition: background 0.2s;"><i
                        class="fas fa-external-link-alt" style="margin-right: 6px;"></i> Live Demo</a>
                <a href="https://github.com/AiruxPH/dynabio" target="_blank"
                    style="padding: 0.6rem 1.2rem; background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 0.9rem; transition: background 0.2s;"><i
                        class="fab fa-github" style="margin-right: 6px;"></i> Source Code</a>
            </div>
        </div>

        <div class="mission-statement">
            <p>"DynaBio is my custom-built digital space—a dynamic, real-time platform designed to showcase my journey,
                share my skills, and present my professional identity without relying on third-party templates."</p>
        </div>

        <div class="feature-grid">
            <div class="feature-card">
                <i class="fas fa-palette feature-icon"></i>
                <h3>Personalized Styling</h3>
                <p>Visitors can instantly switch between 8 pre-built aesthetic themes including Glassmorphism,
                    Cyberpunk, and Solarized styles to view the portfolio from a new perspective.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-shield-alt feature-icon"></i>
                <h3>Backend Architecture</h3>
                <p>The underlying engine is protected by modern PHP PDO standards, secure session management, and robust
                    authentication routing.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-route feature-icon"></i>
                <h3>Interactive Journey</h3>
                <p>Explore a visual, chronological timeline of my education, certifications, and career milestones
                    fetched dynamically from the database.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-file-alt feature-icon"></i>
                <h3>Instant Export</h3>
                <p>Seamlessly compile and download my live biodata, skills, and timeline into a clean, structured
                    plaintext resume with a single click.</p>
            </div>
        </div>

        <div class="about-footer">
            <a href="index.php" class="btn-home">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

    </div>

    <!-- Background Animation (Optional) -->
    <script src="js/background_animation.js"></script>
</body>

</html>