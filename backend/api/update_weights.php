<?php
include_once '../config/database.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method == 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data) && is_array($data)) {
            $conn->beginTransaction();

            try {
                $query = "UPDATE criteria SET weight = :weight WHERE id = :id";
                $stmt = $conn->prepare($query);

                foreach ($data as $item) {
                    if (isset($item['id']) && isset($item['weight'])) {
                        $stmt->bindParam(":weight", $item['weight']);
                        $stmt->bindParam(":id", $item['id']);
                        $stmt->execute();
                    }
                }

                $conn->commit();
                echo json_encode(array("message" => "Weights updated successfully."));
            } catch (Exception $e) {
                $conn->rollBack();
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update weights.", "error" => $e->getMessage()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid data format. Expected an array of objects with id and weight."));
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