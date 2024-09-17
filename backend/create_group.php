<?php
require_once '../config/db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['fonts']) && count($data['fonts']) >= 2) {

    $fonts = json_encode($data['fonts']);

    $query = "INSERT INTO font_groups (fonts) VALUES (:fonts)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':fonts', $fonts);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Font group created successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create font group']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'At least two fonts are required to create a group']);
}
