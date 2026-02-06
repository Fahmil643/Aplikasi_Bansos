<?php
require_once '../config/database.php';

// Fetch reports history
try {
    $stmt = $conn->prepare("SELECT id, name, type, author, created_at as date FROM reports ORDER BY created_at DESC");
    $stmt->execute();
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format date for display
    foreach ($reports as &$report) {
        $report['date'] = date('d M Y, H:i', strtotime($report['date']));
    }

    header('Content-Type: application/json');
    echo json_encode($reports);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Error fetching reports: " . $e->getMessage()]);
}
?>