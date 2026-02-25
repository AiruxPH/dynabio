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

    // Strict Backend Validation
    if ($username !== strtolower($username)) {
        jsonResponse(false, 'Username must be strictly lowercase.');
    }

    if (!preg_match('/^[a-zA-Z0-9](_(?!_)|[a-zA-Z0-9]){2,18}[a-zA-Z0-9]$/', $username)) {
        jsonResponse(false, 'Username must be 4-20 characters, alphanumeric or single underscores, and cannot start/end with an underscore.');
    }

    $reservedWords = ['admin', 'support', 'help', 'root', 'api', 'login', 'signup', 'settings', 'dashboard', 'system', 'staff', 'mod', 'owner', 'blog', 'about', 'contact', 'null', 'undefined'];
    if (in_array($username, $reservedWords)) {
        jsonResponse(false, 'This username is reserved and cannot be used.');
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

        if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
            jsonResponse(false, 'Image size must be less than 2MB.');
        }

        $filename = 'user_' . $user_id . '_' . time() . '.jpg';
        $targetDir = __DIR__ . '/../images/profiles/';

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $sourceFile = $_FILES['photo']['tmp_name'];
        list($origWidth, $origHeight, $imageType) = getimagesize($sourceFile);

        $maxWidth = 500;
        $newWidth = $origWidth;
        $newHeight = $origHeight;

        if ($origWidth > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) floor(($origHeight / $origWidth) * $maxWidth);
        }

        $srcImage = null;
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $srcImage = imagecreatefromjpeg($sourceFile);
                break;
            case IMAGETYPE_PNG:
                $srcImage = imagecreatefrompng($sourceFile);
                break;
            case IMAGETYPE_GIF:
                $srcImage = imagecreatefromgif($sourceFile);
                break;
            case IMAGETYPE_WEBP:
                $srcImage = imagecreatefromwebp($sourceFile);
                break;
        }

        if ($srcImage) {
            $destImage = imagecreatetruecolor($newWidth, $newHeight);

            // Handle transparency for PNG/GIF -> JPG
            if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
                $white = imagecolorallocate($destImage, 255, 255, 255);
                imagefill($destImage, 0, 0, $white);
            }

            imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

            if (imagejpeg($destImage, $targetDir . $filename, 85)) {
                $photoPath = 'images/profiles/' . $filename;
            } else {
                jsonResponse(false, 'Failed to save compressed image.');
            }

            imagedestroy($srcImage);
            imagedestroy($destImage);
        } else {
            jsonResponse(false, 'Failed to process image format.');
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