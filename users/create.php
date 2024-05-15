<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

include '../config.php';  

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Status code 204 means "No Content" and that there will be no body in the response
    http_response_code(204);
    exit();
}

if(isset($data->username) && isset($data->email) && isset($data->password)) {
    $username = $data->username;
    $email = $data->email;
    $password = password_hash($data->password, PASSWORD_DEFAULT); // Hash the password

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if($stmt) {
        $stmt->bind_param("sss", $username, $email, $password);
        if($stmt->execute()) {
            echo json_encode(["message" => "User registered successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "User registration failed"]);
        }
        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error preparing statement"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data provided"]);
}

$conn->close();
?>
