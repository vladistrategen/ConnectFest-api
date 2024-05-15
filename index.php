<?php
$request = $_GET['url'] ?? '';

if ($request == 'read') {
    include 'read.php';
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Resource not found']);
}
