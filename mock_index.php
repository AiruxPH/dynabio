<?php
session_start();
$_SESSION['user_id'] = 1; // mock login
$_SERVER['PHP_SELF'] = '/dynabio/index.php';
$_SERVER['HTTP_HOST'] = 'localhost';

define('ACTUAL_WEB_URL', 'http://localhost/dynabio');

try {
    ob_start();
    require_once __DIR__ . '/index.php';
    $html = ob_get_clean();
    file_put_contents(__DIR__ . '/test_output.html', $html);
    echo "Generated output successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
