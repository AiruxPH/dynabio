<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_utils.php';

if (!isset($_SESSION['user_id'])) {
    jsonResponse(false, 'Unauthorized access.');
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'update_profile') {
    $username = trim($_POST['username'] ?? '');

    if (empty($username)) {
        jsonResponse(false, 'Username cannot be empty.');
    }

    // Check if username is taken by another user
    $check = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
    $check->execute([$username, $user_id]);
    if ($check->fetch()) {
        jsonResponse(false, 'Username is already taken.');
    }

    // Handle Photo Upload
    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
            jsonResponse(false, 'Invalid image format. Allowed: JPG, PNG, GIF, WEBP.');
        }

        // max size 2MB
        if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
            jsonResponse(false, 'Image size must be less than 2MB.');
        }

        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . $user_id . '_' . time() . '.' . $ext;
        $targetDir = __DIR__ . '/../images/profiles/';

        // ensure dir exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetDir . $filename)) {
            $photoPath = 'images/profiles/' . $filename;
        } else {
            jsonResponse(false, 'Failed to upload image.');
        }
    }

    try {
        if ($photoPath) {
            $update = $conn->prepare("UPDATE users SET username = ?, photo = ? WHERE user_id = ?");
            $update->execute([$username, $photoPath, $user_id]);
        } else {
            $update = $conn->prepare("UPDATE users SET username = ? WHERE user_id = ?");
            $update->execute([$username, $user_id]);
        }
        jsonResponse(true, 'Profile updated successfully.');
    } catch (Exception $e) {
        jsonResponse(false, 'Database error: ' . $e->getMessage());
    }
} elseif ($action === 'delete_account') {
    try {
        $update = $conn->prepare("UPDATE users SET is_archived = 1 WHERE user_id = ?");
        $update->execute([$user_id]);

        // Destroy session to log them out immediately
        session_destroy();
        jsonResponse(true, 'Account soft-deleted successfully.');
    } catch (Exception $e) {
        jsonResponse(false, 'Database error: ' . $e->getMessage());
    }
} else {
    jsonResponse(false, 'Invalid action.');
}
?>