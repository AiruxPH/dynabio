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

// Core / Identity Phase 1
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
$sanitized_socials = [];
$allowed_platforms = ['twitter', 'github', 'linkedin', 'instagram', 'youtube', 'facebook', 'website'];
foreach ($social_links as $platform => $url) {
    if (in_array($platform, $allowed_platforms) && filter_var($url, FILTER_VALIDATE_URL)) {
        $sanitized_socials[$platform] = trim($url);
    }
}

// Phase 2 Extended Safeties
$position_desired = isset($data['position_desired']) ? trim($data['position_desired']) : '';
$nickname = isset($data['nickname']) ? trim($data['nickname']) : '';
$present_address = isset($data['present_address']) ? trim($data['present_address']) : '';
$provincial_address = isset($data['provincial_address']) ? trim($data['provincial_address']) : '';
$place_of_birth = isset($data['place_of_birth']) ? trim($data['place_of_birth']) : '';
$citizenship = isset($data['citizenship']) ? trim($data['citizenship']) : '';
$gender = isset($data['gender']) ? trim($data['gender']) : '';
$civil_status = isset($data['civil_status']) ? trim($data['civil_status']) : '';
$religion = isset($data['religion']) ? trim($data['religion']) : '';
$height = isset($data['height']) ? trim($data['height']) : '';
$weight = isset($data['weight']) ? trim($data['weight']) : '';
$github_username = isset($data['github_username']) ? trim($data['github_username']) : '';

$family_background = isset($data['family_background']) && is_array($data['family_background']) ? $data['family_background'] : ['spouse' => '', 'children' => '', 'parents' => ''];


try {
    $stmt = $conn->prepare("SELECT user_id FROM biodata WHERE user_id = ?");
    $stmt->execute([$user_id]);

    if ($stmt->rowCount() > 0) {
        // Phase 2 Update Query
        $sql = "UPDATE biodata SET 
            full_name = ?, tagline = ?, about_me = ?, location = ?, 
            position_desired = ?, nickname = ?, present_address = ?, provincial_address = ?, 
            place_of_birth = ?, citizenship = ?, gender = ?, civil_status = ?, religion = ?, 
            height = ?, weight = ?, family_background = ?, github_username = ?, 
            social_links = ?, skills = ? 
            WHERE user_id = ?";

        $updateStmt = $conn->prepare($sql);
        $updateStmt->execute([
            $full_name,
            $tagline,
            $about_me,
            $location,
            $position_desired,
            $nickname,
            $present_address,
            $provincial_address,
            $place_of_birth,
            $citizenship,
            $gender,
            $civil_status,
            $religion,
            $height,
            $weight,
            json_encode($family_background),
            $github_username,
            json_encode($sanitized_socials),
            json_encode($skills),
            $user_id
        ]);

    } else {
        // Phase 2 Insert Query (Defaulting theme to 'default-glass')
        $sql = "INSERT INTO biodata 
            (user_id, theme, full_name, tagline, about_me, location, position_desired, nickname, present_address, provincial_address, place_of_birth, citizenship, gender, civil_status, religion, height, weight, family_background, github_username, social_links, skills) 
            VALUES (?, 'default-glass', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $insertStmt = $conn->prepare($sql);
        $insertStmt->execute([
            $user_id,
            $full_name,
            $tagline,
            $about_me,
            $location,
            $position_desired,
            $nickname,
            $present_address,
            $provincial_address,
            $place_of_birth,
            $citizenship,
            $gender,
            $civil_status,
            $religion,
            $height,
            $weight,
            json_encode($family_background),
            $github_username,
            json_encode($sanitized_socials),
            json_encode($skills)
        ]);
    }

    echo json_encode(['success' => true, 'message' => 'Biodata successfully saved.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
