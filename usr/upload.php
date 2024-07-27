<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$uploadDir = '../vendor/img/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            echo json_encode(['success' => true, 'imagePath' => $imagePath]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
?>
