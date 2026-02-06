<?php
include_once '../config/database.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method == 'GET') {
        $alternative_id = isset($_GET['alternative_id']) ? $_GET['alternative_id'] : null;

        if ($alternative_id) {
            $query = "SELECT criteria_id, value FROM evaluations WHERE alternative_id = :alternative_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":alternative_id", $alternative_id);
            $stmt->execute();
            $evaluations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($evaluations);
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "alternative_id is required."));
        }
    } elseif ($method == 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data['alternative_id']) && isset($data['values']) && is_array($data['values'])) {
            $alternative_id = $data['alternative_id'];

            $conn->beginTransaction();
            try {
                // Delete existing evaluations for this alternative to avoid duplicates/complex updates
                $queryDel = "DELETE FROM evaluations WHERE alternative_id = :alternative_id";
                $stmtDel = $conn->prepare($queryDel);
                $stmtDel->bindParam(":alternative_id", $alternative_id);
                $stmtDel->execute();

                // Insert new values
                $queryIns = "INSERT INTO evaluations (alternative_id, criteria_id, value) VALUES (:alternative_id, :criteria_id, :value)";
                $stmtIns = $conn->prepare($queryIns);

                foreach ($data['values'] as $item) {
                    $stmtIns->bindParam(":alternative_id", $alternative_id);
                    $stmtIns->bindParam(":criteria_id", $item['criteria_id']);
                    $stmtIns->bindParam(":value", $item['value']);
                    $stmtIns->execute();
                }

                $conn->commit();
                echo json_encode(array("message" => "Evaluations saved."));
            } catch (Exception $e) {
                $conn->rollBack();
                http_response_code(503);
                echo json_encode(array("message" => "Unable to save evaluations.", "error" => $e->getMessage()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid data. alternative_id and values array are required."));
        }
    } elseif ($method == 'OPTIONS') {
        http_response_code(200);
    } else {
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("error" => $e->getMessage()));
}
?>