<?php
require_once __DIR__ . "/Database.php";

class User {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    // Find user by username only (for field-specific validation)
    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // returns row or null
    }
    
    // NEW: Register Function
    public function register($username, $password) {
        // 1. Check if username already exists
        $check = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            return "exists"; // Username taken
        }

        // 2. Hash password and insert
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hash);
        
        if ($stmt->execute()) {
            return "success";
        }
        return "error";
    }
}