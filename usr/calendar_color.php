<?php
session_start();
include('vendor/inc/config.php');

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

    // Checking if the 'events' table exists
    function tableExists($dbh, $tableName) {
        $stmt = $dbh->prepare("SHOW TABLES LIKE :tableName");
        $stmt->execute([':tableName' => $tableName]);
        return $stmt->rowCount() > 0;
    }

    if (!tableExists($db, 'events')) {
        throw new Exception("Table 'events' does not exist.");
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit; // Terminate script if there's a database connection error
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit; // Terminate script if the 'events' table does not exist
}

// Get the input data
$json = file_get_contents('php://input');
$params = json_decode($json);

// Validate input
if (!isset($params->id) || !isset($params->color)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

try {
    // Update the event color
    $insert = "UPDATE events SET color = :color WHERE id = :id";
    $stmt = $db->prepare($insert);
    $stmt->bindParam(':color', $params->color);
    $stmt->bindParam(':id', $params->id);
    $stmt->execute();

    $response = ['status' => 'success', 'message' => 'Update successful'];
} catch (PDOException $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
