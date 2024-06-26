<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
include '../config.php';
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit();
}
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if (isset($input['id']) && isset($input['title']) && isset($input['date']) && isset($input['location']) && isset($input['description']) && isset($input['image_url'])) {
    $sql = $conn->prepare("UPDATE Events SET title = ?, date = ?, location = ?, description = ?, image_url = ? WHERE id = ?");
    $sql->bind_param("sssssi", $input['title'], $input['date'], $input['location'], $input['description'], $input['image_url'], $input['id']);
    
    if ($sql->execute()) {
        echo json_encode(["message" => "Event updated successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to update event"]);
    }
    $sql->close();
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data provided"]);
}

$conn->close();
?>
