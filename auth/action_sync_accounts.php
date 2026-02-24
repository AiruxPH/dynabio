<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_utils.php';

$data = json_decode(file_get_contents('php://input'), true);
$accounts = $data['accounts'] ?? [];

if (!is_array($accounts) || empty($accounts)) {
    echo json_encode(['success' => true, 'synced_accounts' => []]);
    exit;
}

$synced_accounts = [];

foreach ($accounts as $acc) {
    if (!isset($acc['username']))
        continue;

    try {
        if (isset($acc['user_id'])) {
            // Search by ID (immune to username changes)
            $stmt = $conn->prepare("SELECT user_id, username, photo FROM users WHERE user_id = ? AND is_archived = 0 AND is_verified = 1");
            $stmt->execute([$acc['user_id']]);
        } else {
            // Legacy search by username
            $stmt = $conn->prepare("SELECT user_id, username, photo FROM users WHERE username = ? AND is_archived = 0 AND is_verified = 1");
            $stmt->execute([$acc['username']]);
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $synced_accounts[] = [
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'avatar_url' => $user['photo'] ?? 'images/default.png',
                'last_login' => $acc['last_login'] ?? time() // preserve the login history sort
            ];
        }
    } catch (Exception $e) {
        // Just skip on error
    }
}

// Return the cleaned, updated valid list
echo json_encode([
    'success' => true,
    'synced_accounts' => $synced_accounts
]);
?>