<?php
require_once __DIR__ . '/includes/error_handler.php';
require_once __DIR__ . '/includes/db.php';

// Check if username is provided
if (!isset($_GET['u']) || empty(trim($_GET['u']))) {
    $errorState = true;
    $errorMessage = "No profile specified.";
} else {
    $requested_username = trim($_GET['u']);

    // Fetch user and biodata based on username
    $stmt = $conn->prepare("
        SELECT u.user_id, u.photo, b.* 
        FROM users u
        LEFT JOIN biodata b ON u.user_id = b.user_id
        WHERE u.username = ?
    ");
    $stmt->execute([$requested_username]);
    $profile = $stmt->fetch();

    if (!$profile) {
        $errorState = true;
        $errorMessage = "Profile not found.";
    } else {
        $errorState = false;
        $user_id = (int) $profile['user_id'];

        // --- Core Parsing ---
        $theme = !empty($profile['theme']) ? $profile['theme'] : 'default-glass';
        $fullName = !empty($profile['full_name']) ? htmlspecialchars($profile['full_name']) : htmlspecialchars($requested_username);
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

        // --- Privacy Logic ---
        // Address, Citizenship, Civil Status, Weight, Height, and Family Background are deliberately DROPPED 
        // from fetching here to ensure they never leak into the HTML source code of the public URL.

        // --- Timeline Logic ---
        $msStmt = $conn->prepare("SELECT * FROM milestones WHERE user_id = ? ORDER BY milestone_date DESC");
        $msStmt->execute([$user_id]);
        $milestones = $msStmt->fetchAll();

        // --- GitHub Live API & Cache Logic ---
        $githubData = null;
        $githubUsername = !empty($profile['github_username']) ? trim($profile['github_username']) : null;

        if ($githubUsername) {
            $cacheStmt = $conn->prepare("SELECT api_response, updated_at FROM github_cache WHERE user_id = ?");
            $cacheStmt->execute([$user_id]);
            $cache = $cacheStmt->fetch();

            // If cache exists and is less than 4 hours old, use it.
            if ($cache && (time() - strtotime($cache['updated_at'])) < 4 * 3600) {
                $githubData = json_decode($cache['api_response'], true);
            } else {
                // Fetch fresh from GitHub API
                $opts = [
                    'http' => [
                        'method' => 'GET',
                        'header' => [
                            'User-Agent: DynaBio-Portfolio-Engine'
                        ]
                    ]
                ];
                $context = stream_context_create($opts);
                // Fetch user's latest 3 updated public repositories
                $url = "https://api.github.com/users/" . urlencode($githubUsername) . "/repos?sort=updated&per_page=3";
                $response = @file_get_contents($url, false, $context);

                if ($response) {
                    $githubData = json_decode($response, true);

                    // Upsert cache so we don't spam GitHub
                    $upsert = $conn->prepare("INSERT INTO github_cache (user_id, api_response) VALUES (?, ?) ON DUPLICATE KEY UPDATE api_response = VALUES(api_response)");
                    $upsert->execute([$user_id, $response]);
                } else if ($cache) {
                    // Fallback to old cache if GitHub API fails
                    $githubData = json_decode($cache['api_response'], true);
                }
            }
        }
    }
}
?>

<?php
require_once __DIR__ . '/views/public.php';
?>