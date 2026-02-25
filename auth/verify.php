<?php
$email = $_GET['email'] ?? '';
if (empty($email)) {
    header('Location: login.php');
    exit;
}
?>
// --- Load Output Template ---
require_once __DIR__ . '/../views/auth/verify.php';
?>