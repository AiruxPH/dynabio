<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
// --- Load Output Template ---
require_once __DIR__ . '/../views/auth/forgot_password.php';
?>