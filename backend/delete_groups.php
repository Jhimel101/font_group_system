<?php
include '../config/db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {

    $query = "DELETE FROM font_groups WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $data['id']);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Font group deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete font group']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Font group ID is required']);
}
