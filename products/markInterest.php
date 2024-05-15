<? 
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

if (isset($input['event_id'], $input['user_id'])) {
    $sql = $conn->prepare("INSERT INTO User_Events (event_id, user_id) VALUES (?, ?)");
    $sql->bind_param("ii", $input['event_id'], $input['user_id']);
    
    if ($sql->execute()) {
        echo json_encode(["message" => "Interest marked successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to mark interest"]);
    }
    $sql->close();
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data provided"]);
}