<?php
/**
 * Database Connection Page
 * 
 * This file establishes a secure connection to the database using PDO.
 * Credentials are stored in config.php (ignored by Git for security).
 */

require_once __DIR__ . '/config.php';

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $conn = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // In production, you might want to log this instead of dying with the message
    // For now, we'll keep it simple but avoid leaking full stack traces
    die("Database connection failed. Please try again later.");
}
?>