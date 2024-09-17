<?php
require_once '../config/db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$query = "SELECT * FROM font_groups";
$stmt = $db->prepare($query);
$stmt->execute();

$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['groups' => $groups]);
