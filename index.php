<?php
require_once __DIR__ . '/includes/error_handler.php';
require_once __DIR__ . '/includes/auth_check.php';

require_once __DIR__ . '/includes/db.php';

// Fetch the latest user info and their chosen theme
$stmt = $conn->prepare("
    SELECT u.email, u.role, u.photo, u.username, b.theme 
    FROM users u 
    LEFT JOIN biodata b ON u.user_id = b.user_id 
    WHERE u.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    // Failsafe if user was deleted behind the scenes
    session_destroy();
    header("Location: auth/login.php");
    exit();
}

$photo = !empty($user['photo']) ? $user['photo'] : 'user-placeholder.png';
// Fix root pathing for photo
if ($photo !== 'user-placeholder.png' && strpos($photo, '../') === 0) {
    $photo = substr($photo, 3);
}

$displayName = !empty($user['username']) ? htmlspecialchars($user['username']) : htmlspecialchars($user['email']);
$roleName = ucfirst($user['role']);
$currentTheme = !empty($user['theme']) ? $user['theme'] : 'default-glass';

// --- Load Output Template ---
require_once __DIR__ . '/views/dashboard.php';
