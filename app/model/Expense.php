<?php
require_once __DIR__ . "/Database.php";

class Expense {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    // Get all expenses sorted by newest first
    public function getAll() {
        return $this->db->query("SELECT * FROM expenses ORDER BY id DESC");
    }

    // Add a new expense
    public function add($title, $amount, $category) {
        $stmt = $this->db->prepare("INSERT INTO expenses (title, amount, category) VALUES (?, ?, ?)");
        // 'sds' means string, double (decimal number), string
        $stmt->bind_param("sds", $title, $amount, $category);
        return $stmt->execute();
    }

    // Find a single expense for editing
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM expenses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update existing expense
    public function update($id, $title, $amount, $category) {
        $stmt = $this->db->prepare("UPDATE expenses SET title = ?, amount = ?, category = ? WHERE id = ?");
        $stmt->bind_param("sdsi", $title, $amount, $category, $id);
        return $stmt->execute();
    }

    // Delete an expense
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM expenses WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}