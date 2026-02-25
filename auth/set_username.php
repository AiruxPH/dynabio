<?php
session_start();
if (!isset($_SESSION['setup_user_id'])) {
    header('Location: login.php');
    exit;
}
// --- Load Output Template ---
require_once __DIR__ . '/../views/auth/set_username.php';