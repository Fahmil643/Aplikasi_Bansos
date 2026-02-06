<?php
include_once 'config/database.php';

try {
    // Check if rankings are empty
    $stmt = $conn->query("SELECT COUNT(*) FROM rankings");
    $count = $stmt->fetchColumn();
    echo "Current count: $count\n";

    if ($count == 0) {
        $sql = "INSERT INTO rankings (alternative_id, score, rank_position) VALUES 
                (1, 0.9250, 1), 
                (2, 0.8540, 2), 
                (3, 0.8120, 3), 
                (4, 0.7850, 4), 
                (5, 0.7650, 5)";
        $conn->exec($sql);
        echo "Rankings seeded successfully.";
    } else {
        echo "Rankings already exist.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>