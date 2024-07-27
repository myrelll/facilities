<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "vehiclebookings";

try {
    // Establishing a database connection
    $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Setting up timezone
    date_default_timezone_set("UTC");

    // Include file where check_login() function is defined
    include('vendor/inc/checklogin.php');

    // Checking if the 'events' table exists
    function tableExists($dbh, $tableName) {
        $stmt = $dbh->prepare("SHOW TABLES LIKE :tableName");
        $stmt->execute([':tableName' => $tableName]);
        return $stmt->rowCount() > 0;
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit; // Terminate script if there's a database connection error
}

// Check login status
$v_id = $_GET['v_id'] ?? '';

$json = file_get_contents('php://input');
$params = json_decode($json);

$insert = "DELETE FROM events WHERE id = :id";

$stmt = $db->prepare($insert);

$stmt->bindParam(':id', $params->id);

$stmt->execute();

class Result {}

$response = new Result();
$response->result = 'OK';
$response->message = 'Update successful';

header('Content-Type: application/json');
echo json_encode($response);
