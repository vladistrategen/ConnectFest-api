<?php
header("Content-Type: application/json");
include '../config.php'; 

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, title, date, location, description, image_url FROM Events";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $events = [];
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    echo json_encode($events);
} else {
    echo json_encode([]);
}
$conn->close();
