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

if (isset($input['id'])) {
    $sql = $conn->prepare("DELETE FROM Events WHERE id = ?");
    $sql->bind_param("i", $input['id']);

    $sql2 = $conn->prepare("DELETE FROM User_Events WHERE event_id = ?");
    $sql2->bind_param("i", $input['id']);
    if ($sql2->execute()) {
        // continue to delete the event
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to delete event", "error" => $sql2->error]);
        $sql2->close();
        $conn->close();
        exit();
    }
    
    if ($sql->execute()) {
        echo json_encode(["message" => "Event deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to delete event", "error" => $sql->error]);
    }
    $sql->close();
} else {
    http_response_code(400);
    echo json_encode(["message" => "Event ID required"]);
}

$conn->close();
?>
