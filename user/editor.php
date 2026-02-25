<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user data for the navbar
$stmt = $conn->prepare("SELECT email, username, photo FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch existing biodata if any
$stmt = $conn->prepare("SELECT * FROM biodata WHERE user_id = ?");
$stmt->execute([$user_id]);
$biodata = $stmt->fetch();

// Phase 1 Helpers
$fullName = $biodata ? htmlspecialchars((string) $biodata['full_name']) : '';
$tagline = $biodata ? htmlspecialchars((string) $biodata['tagline']) : '';
$aboutMe = $biodata ? htmlspecialchars((string) $biodata['about_me']) : '';
$location = $biodata ? htmlspecialchars((string) $biodata['location']) : '';
$skills = ($biodata && $biodata['skills']) ? $biodata['skills'] : '[]';
$socialLinks = ($biodata && $biodata['social_links']) ? $biodata['social_links'] : '{}';

// Phase 2 Extended Helpers
$positionDesired = $biodata ? htmlspecialchars((string) ($biodata['position_desired'] ?? '')) : '';
$nickname = $biodata ? htmlspecialchars((string) ($biodata['nickname'] ?? '')) : '';
$presentAddress = $biodata ? htmlspecialchars((string) ($biodata['present_address'] ?? '')) : '';
$provincialAddress = $biodata ? htmlspecialchars((string) ($biodata['provincial_address'] ?? '')) : '';
$placeOfBirth = $biodata ? htmlspecialchars((string) ($biodata['place_of_birth'] ?? '')) : '';
$gender = $biodata ? htmlspecialchars((string) ($biodata['gender'] ?? '')) : '';
$civilStatus = $biodata ? htmlspecialchars((string) ($biodata['civil_status'] ?? '')) : '';
$citizenship = $biodata ? htmlspecialchars((string) ($biodata['citizenship'] ?? '')) : '';
$religion = $biodata ? htmlspecialchars((string) ($biodata['religion'] ?? '')) : '';
$height = $biodata ? htmlspecialchars((string) ($biodata['height'] ?? '')) : '';
$weight = $biodata ? htmlspecialchars((string) ($biodata['weight'] ?? '')) : '';
$githubUsername = $biodata ? htmlspecialchars((string) ($biodata['github_username'] ?? '')) : '';

$familyBackground = ($biodata && isset($biodata['family_background']) && $biodata['family_background'])
    ? $biodata['family_background']
    : '{"spouse":"","children":"","parents":""}';


// Fetch Milestones mapping
$milestonesStmt = $conn->prepare("SELECT * FROM milestones WHERE user_id = ? ORDER BY milestone_date DESC");
$milestonesStmt->execute([$user_id]);
$milestones = $milestonesStmt->fetchAll();

?>
// --- Load Output Template ---
require_once __DIR__ . '/../views/editor.php';
?>