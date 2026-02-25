<?php
require_once __DIR__ . '/../includes/auth_check.php';

require_once __DIR__ . '/../includes/db.php';
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND is_archived = 0");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header('Location: ../auth/login.php');
    exit;
}
// --- Load Output Template ---
require_once __DIR__ . '/../views/profile.php';