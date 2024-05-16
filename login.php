<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'config.php';  

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); 

if (!isset($input['username']) || !isset($input['password'])) {
    echo json_encode(['message' => 'Username and password required']);
    exit();
}

$username = $input['username'];
$password = $input['password'];

$sql = "SELECT id, username, is_admin, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// select all events from User_Events where user_id = $user_id

$sql2 = "SELECT * FROM User_Events WHERE user_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$result2 = $stmt2->get_result(); 

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        if ($result2->num_rows > 0) {
            $events = $result2->fetch_assoc();
            echo json_encode(['id'=>$user['id'], 'user' => $user['username'], 'isAdmin' => $user['is_admin'], 'events' => $events]);
        } else {
            echo json_encode(['id'=>$user['id'], 'user' => $user['username'], 'isAdmin' => $user['is_admin'], 'events' => []]);
        }
        
    } else {
        // Password is not correct
        http_response_code(403); 
        echo json_encode(['message' => 'Login failed']);
    }
} else {
    // No user found
    http_response_code(404); 
    echo json_encode(['message' => 'User not found']);
}

$conn->close();
