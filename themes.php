<?php
require_once __DIR__ . '/includes/error_handler.php';
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/db.php';

// Only SITE_OWNER should be able to change the theme
// But technically, the themes are tied to the biodata table, and we ONLY ever show the SITE_OWNER's biodata.
// Let's enforce that only the SITE_OWNER can access this page
$stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['username'] !== SITE_OWNER) {
    // If not the owner, redirect them back to dashboard
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT theme FROM biodata WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$bio = $stmt->fetch();

$currentTheme = !empty($bio['theme']) ? $bio['theme'] : 'default-glass';

// Load the Themes View
require_once __DIR__ . '/views/themes.php';
