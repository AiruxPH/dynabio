<?php
require_once __DIR__ . '/includes/db.php';

try {
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $schema = [];
    if (!empty($tables)) {
        foreach ($tables as $table) {
            $descStmt = $conn->query("DESCRIBE `$table`");
            $schema[$table] = $descStmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    echo json_encode($schema, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>