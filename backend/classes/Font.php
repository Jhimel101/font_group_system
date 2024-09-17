<?php
require_once "../config/db.php";

class Font
{
    private $conn;
    private $table_name = "fonts";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function upload($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error');
        }

        $fileTmpPath = $file['tmp_name'];
        $fileName = $file['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($fileExtension !== 'ttf') {
            throw new Exception('Invalid file type. Only TTF files are allowed.');
        }

        $uploadFileDir = '../fonts/';
        $destPath = $uploadFileDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $query = "INSERT INTO " . $this->table_name . " (name) VALUES (:name)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $fileName);

            if ($stmt->execute()) {
                return $fileName;
            } else {
                throw new Exception('Failed to insert font into database.');
            }
        } else {
            throw new Exception('Failed to move uploaded file.');
        }
    }

    public function getFonts()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
