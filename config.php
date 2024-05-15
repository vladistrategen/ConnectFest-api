<?php
header("Access-Control-Allow-Origin: *"); // Allows all domains
header("Content-Type: application/json"); // Sets the content type
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Specifies methods allowed when accessing the resource
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Specific headers you want to allow
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'ConnectFestDB');

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the connection
if($conn === false){
    die("ERROR: Could not connect. " . $conn->connect_error);
}
?>
