<?php
require_once __DIR__ . '/includes/error_handler.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/config.php';

// Fetch the SITE_OWNER's data
$ownerStmt = $conn->prepare("
    SELECT u.user_id, u.email, b.* 
    FROM users u
    LEFT JOIN biodata b ON u.user_id = b.user_id
    WHERE u.username = ?
");
$ownerStmt->execute([SITE_OWNER]);
$profile = $ownerStmt->fetch();

if (!$profile) {
    die("Error: Profile for '" . SITE_OWNER . "' not found.");
}

$owner_id = (int) $profile['user_id'];
$fullName = !empty($profile['full_name']) ? trim($profile['full_name']) : SITE_OWNER;

// Milestones
$msStmt = $conn->prepare("SELECT title, description, milestone_date FROM milestones WHERE user_id = ? ORDER BY milestone_date DESC");
$msStmt->execute([$owner_id]);
$milestones = $msStmt->fetchAll();

// Skills
$skills = [];
if (!empty($profile['skills'])) {
    $parsed = json_decode($profile['skills'], true);
    if (is_array($parsed))
        $skills = $parsed;
}

// Load the printer-friendly HTML view
require_once __DIR__ . '/views/print.php';
exit;
