<?php
/**
 * ============================================================
 * DATABASE CONNECTION — Database.php
 * ============================================================
 * 1. This class establishes and provides the MySQL database connection.
 * 1.1 Uses MySQLi (MySQL Improved) for secure, prepared-statement queries.
 * 1.2 Connection credentials: host, username, password, database name.
 * ============================================================
 */
class Database {
    // 2. PUBLIC CONNECTION PROPERTY
    // 2.1 Holds the MySQLi connection object, accessible by Model classes
    public $conn;

    /**
     * 3. CONSTRUCTOR: Connects to MySQL when a Database object is created
     */
    public function __construct() {
        // 3.1 Create a new MySQLi connection
        // 3.2 Using 127.0.0.1 instead of 'localhost' to avoid IPv6 resolution
        //     delays that are common in XAMPP on Windows
        $this->conn = new mysqli("127.0.0.1", "root", "", "finance_tracker");

        // 3.3 Check if the connection failed and show a helpful error message
        if ($this->conn->connect_error) {
            die("<div style='padding:20px; border:2px solid red; font-family:sans-serif;'>
                <strong>Database Connection Error:</strong> " . $this->conn->connect_error . " 
                <br>Make sure MySQL is running in XAMPP and the database 'finance_tracker' exists.
            </div>");
        }

        // 3.4 Set character encoding to UTF-8 for proper handling of
        //     special characters like ₹ (Indian Rupee symbol)
        $this->conn->set_charset("utf8mb4");
    }
}