<?php
require_once __DIR__ . '/includes/db.php';

try {
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        echo "No tables found in the database.\n";
    } else {
        foreach ($tables as $table) {
            echo "Table: $table\n";
            $descStmt = $conn->query("DESCRIBE `$table`");
            $columns = $descStmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($columns as $col) {
                echo "  - {$col['Field']} ({$col['Type']}) [Null: {$col['Null']}, Key: {$col['Key']}, Default: {$col['Default']}, Extra: {$col['Extra']}]\n";
            }
            echo "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>