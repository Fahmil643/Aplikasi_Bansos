<?php
include_once '../config/database.php';

header("Content-Type: application/json; charset=UTF-8");

try {
    // 1. Stats
    $stats = [];

    // Total Kriteria
    $stmt = $conn->query("SELECT COUNT(*) FROM criteria");
    $stats['total_kriteria'] = (int) $stmt->fetchColumn();

    // Total Alternatif
    $stmt = $conn->query("SELECT COUNT(*) FROM alternatives");
    $stats['total_alternatif'] = (int) $stmt->fetchColumn();

    // Rasio Konsistensi (Placeholder as per plan)
    $stats['rasio_konsistensi'] = 0.04;

    // Layak Bantuan (Count of ranked alternatives)
    $stmt = $conn->query("SELECT COUNT(*) FROM rankings");
    $stats['layak_bantuan'] = (int) $stmt->fetchColumn();


    // 2. Criteria Distribution
    $criteria_distribution = [];
    $stmt = $conn->query("SELECT code, name, weight FROM criteria ORDER BY code");
    $colors = ['bg-teal-700', 'bg-blue-600', 'bg-indigo-500', 'bg-amber-500', 'bg-rose-500', 'bg-purple-500', 'bg-pink-500', 'bg-cyan-500'];
    $i = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $criteria_distribution[] = [
            "name" => $row['code'] . ": " . $row['name'],
            "value" => (float) $row['weight'] * 100, // Convert 0.35 to 35
            "color" => $colors[$i % count($colors)]
        ];
        $i++;
    }


    // 3. Top Alternatives
    $top_alternatives = [];
    // Join rankings with alternatives
    $query = "SELECT a.name, a.kecamatan, r.score 
              FROM rankings r 
              JOIN alternatives a ON r.alternative_id = a.id 
              ORDER BY r.score DESC 
              LIMIT 5";
    $stmt = $conn->query($query);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $top_alternatives[] = [
            "name" => $row['name'],
            "kecamatan" => $row['kecamatan'],
            "score" => number_format((float) $row['score'], 3)
        ];
    }

    echo json_encode([
        "stats" => $stats,
        "criteria_distribution" => $criteria_distribution,
        "top_alternatives" => $top_alternatives
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>