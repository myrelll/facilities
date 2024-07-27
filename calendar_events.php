<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit; // Terminate script if there's a database connection error
}

// Check login status
$v_id = $_GET['v_id'] ?? '';
$selectedYear = $_GET['selectedYear'] ?? '';

// .events.load() passes start and end as query string parameters by default
$start = $_GET["start"] ?? '';
$end = $_GET["end"] ?? '';

if ($v_id && $selectedYear && $start && $end) {
    try {
        $stmt = $db->prepare('SELECT * FROM events WHERE YEAR(start) = :selectedYear AND YEAR(end) = :selectedYear AND NOT ((end <= :start) OR (start >= :end)) AND facility_id = :v_id');
        $stmt->bindParam(':v_id', $v_id);
        $stmt->bindParam(':selectedYear', $selectedYear);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        class Event {
            public $id;
            public $text;
            public $start;
            public $end;
            public $backColor;
            public $reservee;
        }

        $events = array();

        foreach ($result as $row) {
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

    } catch (PDOException $e) {
        echo json_encode(["error" => "Query failed: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Missing parameters."]);
}
?>
