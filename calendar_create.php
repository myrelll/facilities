<?php
session_start();
include('vendor/inc/config.php');

$host = "localhost";
$username = "root";
$password = "";
$database = "vehiclebookings";

try {
    $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    date_default_timezone_set("UTC");

    include('vendor/inc/checklogin.php');
    check_login();

    // Fetch v_id from request
    $v_id = $_POST['v_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $reservee = $_POST['reservee'] ?? '';
    $start = $_POST['start'] ?? '';
    $end = $_POST['end'] ?? '';

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Define allowed file extensions and check the extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            // Directory where the file will be stored
            $uploadFileDir = './uploaded_images/';
            $dest_path = $uploadFileDir . $fileName;

            // Move the file to the directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // File is uploaded successfully
                $imagePath = $dest_path;
            } else {
                throw new Exception('Error moving the file.');
            }
        } else {
            throw new Exception('Invalid file extension.');
        }
    } else {
        $imagePath = null; // No image uploaded
    }

    // Insert event into the database
    $stmt = $db->prepare("INSERT INTO events (name, reservee, start, end, v_id, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $reservee, $start, $end, $v_id, $imagePath]);

    // Return response
    $response = [
        'id' => $db->lastInsertId(),
        'status' => 'success'
    ];
    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
