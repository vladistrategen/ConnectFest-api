<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'config.php';  // Include your database connection settings

// Get JSON input
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // Convert JSON to array

if (!isset($input['username']) || !isset($input['password'])) {
    echo json_encode(['message' => 'Username and password required']);
    exit();
}

$username = $input['username'];
$password = $input['password'];

// Prepare SQL to prevent SQL injection
$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        // Password is correct
        echo json_encode(['message' => 'Login successful', 'user' => $user['username']]);
    } else {
        // Password is not correct
        http_response_code(403); // Forbidden
        echo json_encode(['message' => 'Login failed']);
    }
} else {
    // No user found
    http_response_code(404); // Not Found
    echo json_encode(['message' => 'User not found']);
}

$conn->close();
