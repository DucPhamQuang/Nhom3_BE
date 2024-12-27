<?php
include('config.php');

class Category {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function addCategory($categoryName) {
        $query = "INSERT INTO category (cat_name) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $categoryName);
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function getAllCategories() {
        $query = "SELECT * FROM category";
        $result = $this->conn->query($query);
        return $result;
    }

    public function updateCategory($catId, $catName) {
        $query = "UPDATE category SET cat_name = ? WHERE cat_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $catName, $catId);
        return $stmt->execute();
    }

    public function deleteCategory($catId) {
        $query = "DELETE FROM category WHERE cat_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $catId);
        return $stmt->execute();
    }
}
