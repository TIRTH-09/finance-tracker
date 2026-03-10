<?php
/**
 * ============================================================
 * USER MODEL — User.php
 * ============================================================
 * 1. This model handles all database operations for the 'users' table.
 * 1.1 Provides methods for: login verification, user lookup, registration.
 * 1.2 Passwords are stored as bcrypt hashes (via password_hash / password_verify).
 * ============================================================
 */

// 2. DEPENDENCY
// 2.1 Include the Database class for MySQL connection
require_once __DIR__ . "/Database.php";

class User {
    // 3. DATABASE CONNECTION PROPERTY
    private $db;

    /**
     * 4. CONSTRUCTOR
     * 4.1 Creates a Database instance and stores the connection
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    /**
     * 5. LOGIN (Combined username + password check)
     * 5.1 Looks up a user by username, then verifies the password hash.
     * 5.2 Returns the user row (associative array) on success, or false on failure.
     * 5.3 NOTE: The auth() method in HomeController uses findByUsername() instead
     *     for field-specific error messages. This method is kept for backward compatibility.
     */
    public function login($username, $password) {
        // 5.4 Prepare a SELECT query to find the user by username
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // 5.5 If user found, verify the password against the stored hash
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                return $user; // 5.5.1 Credentials valid — return user data
            }
        }
        return false; // 5.6 Invalid credentials
    }

    /**
     * 6. FIND USER BY USERNAME
     * 6.1 Looks up a user by username only (no password check).
     * 6.2 Used by HomeController->auth() for field-specific validation:
     *     - First check if username exists (show "Username not found" if not)
     *     - Then check password separately (show "Incorrect password" if wrong)
     * 6.3 Returns the user row, or null if not found.
     */
    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // 6.4 Returns row or null
    }
    
    /**
     * 7. REGISTER A NEW USER
     * 7.1 Creates a new user account with a hashed password.
     * 7.2 Returns 'exists' if username is taken, 'success' if created, 'error' on failure.
     */
    public function register($username, $password) {
        // 7.3 STEP 1: Check if username already exists in the database
        $check = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            return "exists"; // 7.3.1 Username is already taken
        }

        // 7.4 STEP 2: Hash the password using bcrypt (PASSWORD_DEFAULT)
        // 7.4.1 bcrypt automatically generates a random salt
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // 7.5 STEP 3: Insert the new user into the database
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hash);
        
        // 7.6 Return 'success' or 'error' based on query result
        if ($stmt->execute()) {
            return "success";
        }
        return "error";
    }
}