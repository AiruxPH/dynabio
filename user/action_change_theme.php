<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Set JSON response headers
header('Content-Type: application/json');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

// Ensure POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Decode JSON payload
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['theme'])) {
    echo json_encode(['success' => false, 'message' => 'No theme specified.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$new_theme = trim($data['theme']);

// Hardcoded whitelist of allowed valid themes to prevent arbitrary CSS injection
$allowed_themes = [
    'default-glass',
    'neon-cyberpunk',
    'midnight-blue',
    'minimal-light'
];

if (!in_array($new_theme, $allowed_themes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid theme selected.']);
    exit;
}

try {
    // Check if the user already has a biodata row
    $stmt = $pdo->prepare("SELECT user_id FROM biodata WHERE user_id = ?");
    $stmt->execute([$user_id]);

    if ($stmt->rowCount() > 0) {
        // Update the existing row's theme
        $updateStmt = $pdo->prepare("UPDATE biodata SET theme = ? WHERE user_id = ?");
        $updateStmt->execute([$new_theme, $user_id]);
    } else {
        // Create a new blank biodata row specifically setting their theme
        $insertStmt = $pdo->prepare("INSERT INTO biodata (user_id, theme, full_name, tagline, about_me, location, social_links, skills) 
                                     VALUES (?, ?, '', '', '', '', '{}', '[]')");
        $insertStmt->execute([$user_id, $new_theme]);
    }

    echo json_encode(['success' => true, 'message' => 'Theme applied successfully!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
