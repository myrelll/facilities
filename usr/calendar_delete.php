<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$username = "root";
$password = "";
$database = "vehiclebookings";

$response = new stdClass();

try {
    $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    date_default_timezone_set("GMT");
    include('vendor/inc/checklogin.php');

    $json = file_get_contents('php://input');
    $params = json_decode($json);

    if (!$params || !isset($params->id)) {
        throw new Exception('Invalid input data');
    }

    $select = "SELECT name FROM events WHERE id = :id";
    $stmt = $db->prepare($select);
    $stmt->bindParam(':id', $params->id);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        throw new Exception('Event not found');
    }

    $event_name = $event['name'];

    $delete = "DELETE FROM events WHERE id = :id";
    $stmt = $db->prepare($delete);
    $stmt->bindParam(':id', $params->id);
    $stmt->execute();

    $log_insert = "INSERT INTO tms_syslogs (u_email, event_id, booking_time, event_name, action) VALUES (:u_email, :event_id, :booking_time, :event_name, :action)";
    $log_stmt = $db->prepare($log_insert);
    $log_stmt->bindParam(':u_email', $_SESSION['login']);
    $log_stmt->bindParam(':event_id', $params->id);
    $log_stmt->bindParam(':booking_time', date('Y-m-d H:i:s'));
    $log_stmt->bindParam(':event_name', $event_name);
    $action = 'Delete Event';
    $log_stmt->bindParam(':action', $action);

    $log_stmt->execute();

    $response->result = 'OK';
    $response->message = 'Deletion successful';

} catch (PDOException $e) {
    $response->result = 'ERROR';
    $response->message = 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    $response->result = 'ERROR';
    $response->message = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
