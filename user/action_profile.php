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
    // 1. Fetch current user to get valid columns
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$currentUser) {
        jsonResponse(false, 'User not found.');
    }
    $valid_columns = array_keys($currentUser);

    // 2. Define protected fields exactly as in the frontend
    $system_fields = ['user_id', 'password', 'is_verified', 'verification_code', 'code_expires_at', 'created_at', 'photo', 'is_archived'];
    $readonly_fields = ['email', 'role', 'oauth_provider'];

    $update_fields = [];
    $update_values = [];

    // 3. Process $_POST dynamically
    foreach ($_POST as $key => $value) {
        if ($key === 'action')
            continue; // skip the action tag

        $value = trim($value);

        // Skip invalid columns, system fields, and readonly fields
        if (!in_array($key, $valid_columns))
            continue;
        if (in_array($key, $system_fields))
            continue;
        if (in_array($key, $readonly_fields))
            continue;

        // Special Validation for Username
        if ($key === 'username') {
            if (empty($value))
                jsonResponse(false, 'Username cannot be empty.');
            if ($value !== strtolower($value))
                jsonResponse(false, 'Username must be strictly lowercase.');
            if (!preg_match('/^[a-zA-Z0-9](_(?!_)|[a-zA-Z0-9]){2,18}[a-zA-Z0-9]$/', $value)) {
                jsonResponse(false, 'Username must be 4-20 characters, alphanumeric or single underscores, and cannot start/end with an underscore.');
            }
            $reservedWords = ['admin', 'support', 'help', 'root', 'api', 'login', 'signup', 'settings', 'dashboard', 'system', 'staff', 'mod', 'owner', 'blog', 'about', 'contact', 'null', 'undefined'];
            if (in_array($value, $reservedWords))
                jsonResponse(false, 'This username is reserved and cannot be used.');

            // Check uniqueness if changed
            if ($value !== $currentUser['username']) {
                $check = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
                $check->execute([$value, $user_id]);
                if ($check->fetch()) {
                    jsonResponse(false, 'Username is already taken.');
                }
            }
        }

        // Add to update array
        $update_fields[] = "$key = ?";
        $update_values[] = $value;
    }

    // 4. Handle Photo Upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
            jsonResponse(false, 'Invalid image format. Allowed: JPG, PNG, GIF, WEBP.');
        }

        if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
            jsonResponse(false, 'Image size must be less than 2MB.');
        }

        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . $user_id . '_' . time() . '.' . $ext;
        $targetDir = __DIR__ . '/../images/profiles/';

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetDir . $filename)) {
            $update_fields[] = "photo = ?";
            $update_values[] = 'images/profiles/' . $filename;
        } else {
            jsonResponse(false, 'Failed to upload image.');
        }
    }

    // 5. Execute Dynamic Update
    if (!empty($update_fields)) {
        try {
            $update_values[] = $user_id; // append for the WHERE clause
            $sql = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE user_id = ?";
            $update = $conn->prepare($sql);
            $update->execute($update_values);
            jsonResponse(true, 'Profile updated successfully.');
        } catch (Exception $e) {
            jsonResponse(false, 'Database error: ' . $e->getMessage());
        }
    } else {
        jsonResponse(true, 'No changes to update.');
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