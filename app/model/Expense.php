<?php
require_once __DIR__ . "/Database.php";

class Expense {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM expenses ORDER BY id DESC");
    }

    public function add($title, $amount, $category) {
        $stmt = $this->db->prepare("INSERT INTO expenses (title, amount, category) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $title, $amount, $category);
        return $stmt->execute();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM expenses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $title, $amount, $category) {
        $stmt = $this->db->prepare("UPDATE expenses SET title = ?, amount = ?, category = ? WHERE id = ?");
        $stmt->bind_param("sdsi", $title, $amount, $category, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM expenses WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}