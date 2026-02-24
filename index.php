<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

require_once __DIR__ . '/includes/db.php';

// Fetch the latest user info
$stmt = $conn->prepare("SELECT email, role, photo, username FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    // Failsafe if user was deleted behind the scenes
    session_destroy();
    header("Location: auth/login.php");
    exit();
}

$photo = !empty($user['photo']) ? $user['photo'] : 'images/default.png';
$displayName = !empty($user['username']) ? htmlspecialchars($user['username']) : htmlspecialchars($user['email']);
$roleName = ucfirst($user['role']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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

    <div class="container">
        <div class="welcome-card">
            <img src="<?php echo htmlspecialchars($photo); ?>" alt="Profile Photo" class="profile-photo"
                onerror="this.src='images/default.png'">

            <div class="welcome-text">
                <h1>Welcome back,
                    <?php echo $displayName; ?>!
                </h1>
                <p>You have successfully authenticated into the secure system.</p>
                <div class="badge">
                    <?php echo $roleName; ?> Account
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/includes/username_modal.php'; ?>
    <script src="js/background_animation.js"></script>
</body>

</html>