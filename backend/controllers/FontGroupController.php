<?php
require_once '../config/db.php';
require_once '../classes/FontGroup.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Handle OPTIONS request (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$fontGroup = new FontGroup($db);

// Handle POST request (Create font group)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        if (!isset($data['group_title']) || !isset($data['fonts']) || count($data['fonts']) < 2) {
            throw new Exception('Group title and at least two fonts are required');
        }

        $specific_size = isset($data['specific_size']) ? $data['specific_size'] : null;
        $price_change = isset($data['price_change']) ? $data['price_change'] : 0;

        // Create font group
        $fontGroup->create($data['group_title'], $data['fonts'], $specific_size, $price_change);
        http_response_code(201); // Created
        echo json_encode(['status' => 'success', 'message' => 'Font group created successfully']);
    } catch (Exception $e) {
        http_response_code(400); // Bad request
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

// Handle GET request (Fetch all font groups)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['groups' => $fontGroup->getGroups()]);
}

// Handle DELETE request (Delete font group)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        if (!isset($data['id'])) {
            throw new Exception('Font group ID is required');
        }

        $fontGroup->deleteGroup($data['id']);
        http_response_code(200); // Success
        echo json_encode(['status' => 'success', 'message' => 'Font group deleted']);
    } catch (Exception $e) {
        http_response_code(400); // Bad request
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

// Handle PUT request (Update font group)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        if (!isset($data['id']) || !isset($data['group_title']) || !isset($data['fonts'])) {
            throw new Exception('ID, group title, and at least two fonts are required');
        }

        // Optional fields
        $specific_size = isset($data['specific_size']) ? $data['specific_size'] : null;
        $price_change = isset($data['price_change']) ? $data['price_change'] : 0;

        // Update font group
        $fontGroup->update($data['id'], $data['group_title'], $data['fonts'], $specific_size, $price_change);
        http_response_code(200); // Success
        echo json_encode(['status' => 'success', 'message' => 'Font group updated']);
    } catch (Exception $e) {
        http_response_code(400); // Bad request
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
