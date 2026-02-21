<?php
/**
 * Verification Script for Database Connection
 */

// Enable error reporting for this test script
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once __DIR__ . '/db.php';

    if (isset($conn) && $conn instanceof PDO) {
        echo "<h2 style='color: green;'>SUCCESS: Database connection established using PDO.</h2>";

        // Test a simple query
        $query = $conn->query("SELECT VERSION() as version");
        $row = $query->fetch();
        echo "<p>Connected to MySQL version: <strong>" . $row['version'] . "</strong></p>";

        // List tables to see if they exist
        echo "<h3>Tables in " . DB_NAME . ":</h3><ul>";
        $tables = $conn->query("SHOW TABLES");
        while ($table = $tables->fetch(PDO::FETCH_NUM)) {
            echo "<li>" . $table[0] . "</li>";
        }
        echo "</ul>";

    } else {
        echo "<h2 style='color: red;'>FAILURE: Database connection could not be verified.</h2>";
    }
} catch (Exception $e) {
    echo "<h2 style='color: red;'>ERROR:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
