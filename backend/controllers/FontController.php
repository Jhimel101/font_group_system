<?php
require_once '../config/db.php';
require_once '../classes/Font.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


$database = new Database();
$db = $database->getConnection();

$font = new Font($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $fontName = $font->upload($_FILES['fontFile']);
        echo json_encode(['status' => 'success', 'font' => $fontName]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];
    $query = "DELETE FROM fonts WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete font']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['fonts' => $font->getFonts()]);
}
