<?php
include_once '../config/database.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method == 'GET') {
        $stmt = $conn->query("SELECT * FROM alternatives ORDER BY created_at DESC");
        $alternatives = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($alternatives);
    } elseif ($method == 'POST') {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->name) && !empty($data->nik)) {
            $query = "INSERT INTO alternatives (name, nik, address, kecamatan, status) VALUES (:name, :nik, :address, :kecamatan, :status)";
            $stmt = $conn->prepare($query);

            $status = !empty($data->status) ? $data->status : 'active';

            $stmt->bindParam(":name", $data->name);
            $stmt->bindParam(":nik", $data->nik);
            $stmt->bindParam(":address", $data->address);
            $stmt->bindParam(":kecamatan", $data->kecamatan);
            $stmt->bindParam(":status", $status);

            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(array("message" => "Alternative created."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create alternative."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data. name and nik are required."));
        }
    } elseif ($method == 'PUT') {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id)) {
            $fields = [];
            $params = [];

            if (isset($data->name)) {
                $fields[] = "name = :name";
                $params[':name'] = $data->name;
            }
            if (isset($data->nik)) {
                $fields[] = "nik = :nik";
                $params[':nik'] = $data->nik;
            }
            if (isset($data->address)) {
                $fields[] = "address = :address";
                $params[':address'] = $data->address;
            }
            if (isset($data->kecamatan)) {
                $fields[] = "kecamatan = :kecamatan";
                $params[':kecamatan'] = $data->kecamatan;
            }
            if (isset($data->status)) {
                $fields[] = "status = :status";
                $params[':status'] = $data->status;
            }

            if (empty($fields)) {
                http_response_code(400);
                echo json_encode(array("message" => "No fields to update."));
                exit;
            }

            $params[':id'] = $data->id;

            $query = "UPDATE alternatives SET " . implode(", ", $fields) . " WHERE id = :id";
            $stmt = $conn->prepare($query);

            if ($stmt->execute($params)) {
                echo json_encode(array("message" => "Alternative updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update alternative."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID is required for update."));
        }
    } elseif ($method == 'DELETE') {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if (!empty($id)) {
            $query = "DELETE FROM alternatives WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {
                echo json_encode(array("message" => "Alternative deleted."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to delete alternative."));
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
