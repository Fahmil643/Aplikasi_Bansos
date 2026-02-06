<?php
include_once '../config/database.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    try {
        // 1. Fetch ALL Criteria to get Weights and Types
        $stmtCriteria = $conn->query("SELECT * FROM criteria");
        $criteria = $stmtCriteria->fetchAll(PDO::FETCH_ASSOC);

        // 2. Fetch ALL Alternatives
        $stmtAlt = $conn->query("SELECT * FROM alternatives WHERE status = 'active'"); // Only rank active ones
        $alternatives = $stmtAlt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Fetch ALL Evaluations
        $stmtEval = $conn->query("SELECT * FROM evaluations");
        $evaluations = $stmtEval->fetchAll(PDO::FETCH_ASSOC);

        // Organized Evaluations: [alternative_id][criteria_id] = value
        $evalMap = [];
        $criteriaValues = []; // [criteria_id] => [val1, val2, ...] for finding Min/Max

        foreach ($evaluations as $ev) {
            $evalMap[$ev['alternative_id']][$ev['criteria_id']] = $ev['value'];
            $criteriaValues[$ev['criteria_id']][] = $ev['value'];
        }

        // Find Min/Max for Normalization
        $minMax = [];
        foreach ($criteria as $c) {
            $vals = isset($criteriaValues[$c['id']]) ? $criteriaValues[$c['id']] : [0];
            $minMax[$c['id']] = [
                'min' => min($vals),
                'max' => max($vals)
            ];
        }

        // Calculate Scores
        $rankedResults = [];

        foreach ($alternatives as $alt) {
            $finalScore = 0;
            $altId = $alt['id'];
            $hasData = true;

            foreach ($criteria as $c) {
                $cId = $c['id'];
                $weight = floatval($c['weight']);
                $type = $c['type'];

                $value = isset($evalMap[$altId][$cId]) ? floatval($evalMap[$altId][$cId]) : 0;

                // Normalization
                $normalized = 0;
                $min = $minMax[$cId]['min'];
                $max = $minMax[$cId]['max'];

                if ($max > 0) { // Avoid division by zero
                    if ($type == 'benefit') {
                        // Benefit: Val / Max
                        $normalized = $value / $max;
                    } else {
                        // Cost: Min / Val (Handle zero val edge case?)
                        // Standard Cost formula: Min / Val. If Val is 0, this is valid primarily if Min is 0, giving 0? 
                        // To avoid div by 0 let's assume if val is 0 -> 0.
                        if ($value > 0) {
                            $normalized = $min / $value;
                        } else {
                            $normalized = 0;
                        }
                    }
                }

                $finalScore += $normalized * $weight;
            }

            // Threshold for eligibility (e.g., > 0.5 or just logic based) -> We can verify later
            // For now simple calculation

            $rankedResults[] = [
                'id' => $alt['id'],
                'name' => $alt['name'],
                'kecamatan' => $alt['kecamatan'],
                'score' => $finalScore,
                'isEligible' => $finalScore >= 0.70 // Example threshold
            ];
        }

        // Sort by Score Descending
        usort($rankedResults, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        echo json_encode($rankedResults);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("error" => $e->getMessage()));
    }
} elseif ($method == 'OPTIONS') {
    http_response_code(200);
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>