<?php
require_once '../config/db.php';


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$query = "SELECT * FROM fonts";
$stmt = $db->prepare($query);
$stmt->execute();

$fonts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['fonts' => $fonts]);
