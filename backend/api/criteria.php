<?php
include_once '../config/database.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method == 'GET') {
        $stmt = $conn->query("SELECT * FROM criteria ORDER BY code");
        $criteria = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($criteria);
    } elseif ($method == 'POST') {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->code) && !empty($data->name) && isset($data->weight) && !empty($data->type)) {
            $query = "INSERT INTO criteria (code, name, weight, type) VALUES (:code, :name, :weight, :type)";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(":code", $data->code);
            $stmt->bindParam(":name", $data->name);
            $stmt->bindParam(":weight", $data->weight);
            $stmt->bindParam(":type", $data->type);

            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(array("message" => "Criteria created."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create criteria."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data. code, name, weight, and type are required."));
        }
    } elseif ($method == 'PUT') {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id)) {
            $fields = [];
            $params = [];

            if (isset($data->code)) {
                $fields[] = "code = :code";
                $params[':code'] = $data->code;
            }
            if (isset($data->name)) {
                $fields[] = "name = :name";
                $params[':name'] = $data->name;
            }
            if (isset($data->weight)) {
                $fields[] = "weight = :weight";
                $params[':weight'] = $data->weight;
            }
            if (isset($data->type)) {
                $fields[] = "type = :type";
                $params[':type'] = $data->type;
            }

            if (empty($fields)) {
                http_response_code(400);
                echo json_encode(array("message" => "No fields to update."));
                exit;
            }

            $params[':id'] = $data->id;

            $query = "UPDATE criteria SET " . implode(", ", $fields) . " WHERE id = :id";
            $stmt = $conn->prepare($query);

            if ($stmt->execute($params)) {
                echo json_encode(array("message" => "Criteria updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update criteria."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID is required for update."));
        }
    } elseif ($method == 'DELETE') {
        $data = json_decode(file_get_contents("php://input"));

        // Support ID passed in URL query param for easier testing/calls if needed, but standard is body or param
        $id = isset($_GET['id']) ? $_GET['id'] : (isset($data->id) ? $data->id : null);

        if (!empty($id)) {
            $query = "DELETE FROM criteria WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {
                echo json_encode(array("message" => "Criteria deleted."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to delete criteria."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID is required for deletion."));
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