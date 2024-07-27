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

// .events.load() passes start and end as query string parameters by default
$start = $_GET["start"];
$end = $_GET["end"];
    
$stmt = $db->prepare('SELECT * FROM events WHERE NOT ((end <= :start) OR (start >= :end)) AND facility_id = :v_id');
$stmt->bindParam(':v_id', $v_id);

$stmt->bindParam(':start', $start);
$stmt->bindParam(':end', $end);

$stmt->execute();
$result = $stmt->fetchAll();

class Event {}
$events = array();

foreach($result as $row) {
    $e = new Event();
    $e->id = $row['id'];
    $e->text = $row['name'];
    $e->start = $row['start'];
    $e->end = $row['end'];
    $e->backColor = $row['color'];
    $e->reservee = $row['reserved_by'];
    $events[] = $e;
  }
  

header('Content-Type: application/json');
echo json_encode($events);
