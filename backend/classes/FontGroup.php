<?php
require_once '../config/db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

class FontGroup
{
    private $conn;
    private $table = "font_groups";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new font group with group title, fonts, specific size, and price change
    public function create($group_title, $fonts, $specific_size = null, $price_change = 0)
    {
        if (count($fonts) < 2) {
            throw new Exception("At least two fonts are required to create a group");
        }

        $query = "INSERT INTO " . $this->table . " (group_title, fonts, specific_size, price_change) VALUES (:group_title, :fonts, :specific_size, :price_change)";
        $stmt = $this->conn->prepare($query);

        $fonts_json = json_encode($fonts);  // Encode fonts to JSON

        $stmt->bindParam(":group_title", $group_title);
        $stmt->bindParam(":fonts", $fonts_json);
        $stmt->bindParam(":specific_size", $specific_size);
        $stmt->bindParam(":price_change", $price_change);

        $stmt->execute();
    }

    // Get all font groups
    public function getGroups()
    {
        $query = "SELECT *, JSON_LENGTH(fonts) as font_count FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update a font group
    public function update($id, $group_title, $fonts, $specific_size = null, $price_change = 0)
    {
        $query = "UPDATE " . $this->table . " SET group_title = :group_title, fonts = :fonts, specific_size = :specific_size, price_change = :price_change WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':group_title', $group_title);
        $stmt->bindParam(':fonts', json_encode($fonts));
        $stmt->bindParam(':specific_size', $specific_size);
        $stmt->bindParam(':price_change', $price_change);
        $stmt->bindParam(':id', $id);
        if (!$stmt->execute()) {
            throw new Exception('Failed to update font group');
        }
    }

    // Delete a font group
    public function deleteGroup($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }
}
