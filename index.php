<?php
require_once __DIR__ . '/includes/error_handler.php';
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/db.php';

// 1. Fetch the logged-in user's basic info for Auth/Navbar checks
$stmt = $conn->prepare("SELECT email, role, photo, username FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$loggedInUser = $stmt->fetch();

if (!$loggedInUser) {
    session_destroy();
    header("Location: auth/login.php");
    exit();
}

// 2. Fetch the SITE_OWNER's public portfolio data to display on the dashboard
$ownerStmt = $conn->prepare("
    SELECT u.user_id, u.photo, b.* 
    FROM users u
    LEFT JOIN biodata b ON u.user_id = b.user_id
    WHERE u.username = ?
");
$ownerStmt->execute([SITE_OWNER]);
$profile = $ownerStmt->fetch();

if (!$profile) {
    die("Site Owner '" . SITE_OWNER . "' not found in the database. Please ensure the user exists before viewing the dashboard.");
}

$owner_id = (int) $profile['user_id'];
$isOwnerEmpty = empty($profile['theme']); // If they have no theme, they likely haven't set up biodata

// --- Core Parsing ---
$currentTheme = !empty($profile['theme']) ? $profile['theme'] : 'default-glass';
$fullName = !empty($profile['full_name']) ? htmlspecialchars($profile['full_name']) : htmlspecialchars(SITE_OWNER);
$tagline = !empty($profile['tagline']) ? htmlspecialchars($profile['tagline']) : '';
$aboutMe = !empty($profile['about_me']) ? nl2br(htmlspecialchars($profile['about_me'])) : '';
$location = !empty($profile['location']) ? htmlspecialchars($profile['location']) : '';

// Fix photo path
$photo = !empty($profile['photo']) ? $profile['photo'] : 'user-placeholder.png';
if ($photo !== 'user-placeholder.png' && strpos($photo, '../') === 0) {
    $photo = substr($photo, 3);
}

// --- Arrays ---
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

$platformIcons = [
    'twitter' => 'fa-x-twitter',
    'github' => 'fa-github',
    'linkedin' => 'fa-linkedin-in',
    'instagram' => 'fa-instagram',
    'youtube' => 'fa-youtube',
    'facebook' => 'fa-facebook-f',
    'website' => 'fa-globe'
];

// --- Timeline Logic ---
$msStmt = $conn->prepare("SELECT * FROM milestones WHERE user_id = ? ORDER BY milestone_date DESC");
$msStmt->execute([$owner_id]);
$milestones = $msStmt->fetchAll();

// --- GitHub Logic ---
$githubData = null;
$githubUsername = !empty($profile['github_username']) ? trim($profile['github_username']) : null;
// (Simplified for dashboard without cache to keep file small, or assume background sync)

// --- Pass Variables to View ---
// We explicitly distinguish between the viewer ($loggedInUser) and the content owner
$isSiteOwner = ($loggedInUser['username'] === SITE_OWNER);

require_once __DIR__ . '/views/dashboard.php';

