<?php
require_once '../config/db.php';

// Enable CORS if frontend is on a different port
header("Access-Control-Allow-Origin: *");  // Allow requests from any origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");  // Allow specific HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Check if a file has been uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['font']) && $_FILES['font']['error'] === UPLOAD_ERR_OK) {
        // Validate the file type (only allow TTF files)
        $fileTmpPath = $_FILES['font']['tmp_name'];
        $fileName = $_FILES['font']['name'];
        $fileSize = $_FILES['font']['size'];
        $fileType = $_FILES['font']['type'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($fileExtension === 'ttf') {
            // Move the file to the fonts directory
            $uploadFileDir = './fonts/';
            $destPath = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Insert font into database
                $query = "INSERT INTO fonts (name) VALUES (:name)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':name', $fileName);

                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Font uploaded and saved to database successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to insert font into database']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only TTF files are allowed']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded or upload error']);
    }
}
