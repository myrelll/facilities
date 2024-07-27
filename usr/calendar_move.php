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

// Read and decode JSON input
$json = file_get_contents('php://input');
$params = json_decode($json);

// Debugging: Output the JSON payload for inspection
if (json_last_error() !== JSON_ERROR_NONE) {
    $response = new stdClass();
    $response->result = 'ERROR';
    $response->message = 'Invalid JSON format';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Debugging: Check the decoded JSON object
if (!$params || !isset($params->id) || !isset($params->newStart) || !isset($params->newEnd)) {
    $response = new stdClass();
    $response->result = 'ERROR';
    $response->message = 'Missing required input data. Received: ' . json_encode($params);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Retrieve current event details before updating
$select = "SELECT name, start, end FROM events WHERE id = :id";
$stmt = $db->prepare($select);
$stmt->bindParam(':id', $params->id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    $response = new stdClass();
    $response->result = 'ERROR';
    $response->message = 'Event not found';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$event_name = $event['name'];

// Update the event
$update = "UPDATE events SET start = :start, end = :end WHERE id = :id";
$stmt = $db->prepare($update);
$stmt->bindParam(':start', $params->newStart);
$stmt->bindParam(':end', $params->newEnd);
$stmt->bindParam(':id', $params->id);
$stmt->execute();

// Log the move
$log_insert = "INSERT INTO tms_syslogs (u_email, event_id, booking_time, event_name, action) VALUES (:u_email, :event_id, :booking_time, :event_name, :action)";
$log_stmt = $db->prepare($log_insert);

// Assuming user email is stored in session
$log_stmt->bindParam(':u_email', $_SESSION['login']);
$log_stmt->bindParam(':event_id', $params->id); // Use the ID of the deleted event
$log_stmt->bindParam(':booking_time', date('Y-m-d H:i:s')); // Current timestamp
$log_stmt->bindParam(':event_name', $event_name); // Use the retrieved event name
$action = 'Move Event';
$log_stmt->bindParam(':action', $action); // Set action to 'Delete_event'

$log_stmt->execute();

$response = new stdClass();
$response->result = 'OK';
$response->message = 'Move successful';

header('Content-Type: application/json');
echo json_encode($response);
?>
