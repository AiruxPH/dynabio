<?php
session_start();
if (!isset($_SESSION['verified_email'])) {
    header('Location: login.php');
    exit;
}
?>
// --- Load Output Template ---
require_once __DIR__ . '/../views/auth/set_password.php';
?>