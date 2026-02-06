<?php
include_once 'config/database.php';

try {
    $tables = ['users', 'criteria', 'alternatives', 'evaluations', 'rankings'];
    foreach ($tables as $table) {
        $stmt = $conn->query("SELECT COUNT(*) FROM $table");
        echo "$table: " . $stmt->fetchColumn() . "\n";
    }

    // Check IDs of alternatives
    $stmt = $conn->query("SELECT id, name FROM alternatives");
    echo "Alternatives IDs:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['id'] . ": " . $row['name'] . "\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>