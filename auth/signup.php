<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
// --- Load Output Template ---
require_once __DIR__ . '/../views/auth/signup.php';