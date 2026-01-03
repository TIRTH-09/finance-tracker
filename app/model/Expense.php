<?php
require_once __DIR__ . "/Database.php";

class Expense
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM expenses ORDER BY id DESC");
    }

    public function add($title, $amount)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO expenses (title, amount) VALUES (?, ?)"
        );
        $stmt->bind_param("sd", $title, $amount);
        $stmt->execute();
    }
}
