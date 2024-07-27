<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "vehiclebookings";

try {
    $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $uploadDir = 'uploads/events/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $fileName = basename($_FILES['image']['name']);
            $targetFilePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imagePath = $targetFilePath;
            } else {
                echo json_encode(['success' => false, 'error' => 'File upload failed.']);
                exit;
            }
        }

        if (isset($_POST['start'], $_POST['end'], $_POST['name'], $_POST['reserved_by'])) {
            $facility_id = $_POST['v_id'];
            $stmt = $db->prepare("INSERT INTO events (facility_id, reserved_by, name, start, end, image) VALUES (:facility_id, :reserved_by, :name, :start, :end, :image)");
            $stmt->bindValue(':facility_id', $facility_id);
            $stmt->bindValue(':start', $_POST['start']);
            $stmt->bindValue(':end', $_POST['end']);
            $stmt->bindValue(':name', $_POST['name']);
            $stmt->bindValue(':reserved_by', $_POST['reserved_by']);
            $stmt->bindValue(':image', $imagePath);
            $stmt->execute();

            $event_id = $db->lastInsertId();

            // Logging the event creation
            $u_email = $_SESSION['login'];
            $booking_time = date('Y-m-d H:i:s');
            $log_stmt = $db->prepare("INSERT INTO tms_syslogs (u_email, event_id, booking_time, event_name, action) VALUES (:u_email, :event_id, :booking_time, :event_name, :action)");
            $log_stmt->bindValue(':u_email', $u_email);
            $log_stmt->bindValue(':event_id', $event_id);
            $log_stmt->bindValue(':booking_time', $booking_time);
            $log_stmt->bindValue(':event_name', $_POST['name']);
            $log_stmt->bindValue(':action', 'Created Event');
            $log_stmt->execute();

            // Return JSON response
            echo json_encode(['success' => true, 'id' => $event_id]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
