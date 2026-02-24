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
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
        }

        /* Simple Navbar */
        .navbar {
            background-color: #1e293b;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #334155;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: #f8fafc;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logout-btn {
            padding: 0.5rem 1rem;
            background-color: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background-color: rgba(239, 68, 68, 0.2);
            color: #fecaca;
        }

        /* Dashboard Content */
        .container {
            max-width: 1000px;
            margin: 3rem auto;
            padding: 0 1.5rem;
        }

        .welcome-card {
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3b82f6;
            background-color: #0f172a;
        }

        .welcome-text h1 {
            margin: 0 0 0.5rem 0;
            font-size: 1.75rem;
            color: #f8fafc;
        }

        .welcome-text p {
            margin: 0;
            color: #94a3b8;
        }

        .badge {
            display: inline-block;
            background-color: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.75rem;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
    </style>
</head>

<body>

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
</body>

</html>