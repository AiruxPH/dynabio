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

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON payload.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$full_name = isset($data['full_name']) ? trim($data['full_name']) : '';
$tagline = isset($data['tagline']) ? trim($data['tagline']) : '';
$about_me = isset($data['about_me']) ? trim($data['about_me']) : '';
$location = isset($data['location']) ? trim($data['location']) : '';

// Validation check on array sizes to prevent arbitrary huge JSON dumping
$skills = isset($data['skills']) && is_array($data['skills']) ? $data['skills'] : [];
if (count($skills) > 15) {
    $skills = array_slice($skills, 0, 15);
}

$social_links = isset($data['social_links']) && is_array($data['social_links']) ? $data['social_links'] : [];
// Sanitize social links (ensure all values are URLs or at least strings)
$sanitized_socials = [];
$allowed_platforms = ['twitter', 'github', 'linkedin', 'instagram', 'youtube', 'facebook', 'website'];
foreach ($social_links as $platform => $url) {
    if (in_array($platform, $allowed_platforms) && filter_var($url, FILTER_VALIDATE_URL)) {
        $sanitized_socials[$platform] = trim($url);
    }
}


try {
    // We use an UPSERT (INSERT ... ON DUPLICATE KEY UPDATE) logic
    // But since MySQL doesn't natively do upserts cleanly without knowing the keys perfectly, 
    // doing a SELECT first is safer for our custom architecture.

    $stmt = $conn->prepare("SELECT user_id FROM biodata WHERE user_id = ?");
    $stmt->execute([$user_id]);

    if ($stmt->rowCount() > 0) {
        // UPDATE existing row (Note: We do NOT update `theme` here, that's handled cleanly on the dashboard)
        $updateStmt = $conn->prepare("UPDATE biodata 
                                     SET full_name = ?, tagline = ?, about_me = ?, location = ?, social_links = ?, skills = ? 
                                     WHERE user_id = ?");
        $updateStmt->execute([
            $full_name,
            $tagline,
            $about_me,
            $location,
            json_encode($sanitized_socials),
            json_encode($skills),
            $user_id
        ]);
    } else {
        // INSERT new row (Defaulting theme to 'default-glass')
        $insertStmt = $conn->prepare("INSERT INTO biodata 
                                    (user_id, theme, full_name, tagline, about_me, location, social_links, skills) 
                                    VALUES (?, 'default-glass', ?, ?, ?, ?, ?, ?)");
        $insertStmt->execute([
            $user_id,
            $full_name,
            $tagline,
            $about_me,
            $location,
            json_encode($sanitized_socials),
            json_encode($skills)
        ]);
    }

    echo json_encode(['success' => true, 'message' => 'Biodata successfully saved.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
