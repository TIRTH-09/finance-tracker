<?php
class Database {
    public $conn;
    public function __construct() {
        // Using 127.0.0.1 avoids IPv6 resolution delays common in XAMPP
        $this->conn = new mysqli("127.0.0.1", "root", "", "finance_tracker");

        if ($this->conn->connect_error) {
            die("<div style='padding:20px; border:2px solid red; font-family:sans-serif;'>
                <strong>Database Connection Error:</strong> " . $this->conn->connect_error . " 
                <br>Make sure MySQL is running in XAMPP and the database 'finance_tracker' exists.
            </div>");
        }
        $this->conn->set_charset("utf8mb4");
    }
}