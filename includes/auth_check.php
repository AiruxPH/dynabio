<?php
// includes/auth_check.php
require_once __DIR__ . '/error_handler.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {

    // Determine the correct path to login.php depending on where this is included from
    $current_dir = basename(dirname($_SERVER['PHP_SELF']));
    $login_path = ($current_dir === 'user') ? '../auth/login.php' : 'auth/login.php';

    header("Location: " . $login_path);
    exit();
}
?>