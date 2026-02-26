<?php
/**
 * Database Connection Page
 * 
 * This file establishes a secure connection to the database using PDO.
 * Credentials are stored in config.php (ignored by Git for security).
 */
$config_path = __DIR__ . '/config.php';

if (!file_exists($config_path)) {
    // Graceful fallback for localhost clones without a database configuration
    die('
    <div style="font-family: system-ui, sans-serif; background: #0f172a; color: #f8fafc; height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; margin: 0;">
        <h1 style="color: #60a5fa; margin-bottom: 0.5rem;">Configuration Missing</h1>
        <p style="color: #94a3b8; max-width: 500px; line-height: 1.6;">It looks like you cloned this repository locally but haven\'t set up the <code>includes/config.php</code> database credentials yet.</p>
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <a href="https://dynabio.ccsblock2.com" style="padding: 0.75rem 1.5rem; background: #3b82f6; color: white; text-decoration: none; border-radius: 8px; font-weight: 500;">View Live Demo</a>
            <a href="https://github.com/AiruxPH/dynabio" style="padding: 0.75rem 1.5rem; background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); text-decoration: none; border-radius: 8px; font-weight: 500;">View Repository</a>
        </div>
    </div>
    ');
}

require_once $config_path;

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