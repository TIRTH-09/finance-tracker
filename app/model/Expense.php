<?php
/**
 * ============================================================
 * EXPENSE MODEL — Expense.php
 * ============================================================
 * 1. This model handles all database operations for the 'expenses' table.
 * 1.1 Provides CRUD methods: getAll, add, find, update, delete.
 * 1.2 Uses prepared statements to prevent SQL injection attacks.
 * ============================================================
 */

// 2. DEPENDENCY
// 2.1 Include the Database class for MySQL connection
require_once __DIR__ . "/Database.php";

class Expense {
    // 3. DATABASE CONNECTION PROPERTY
    // 3.1 Private — only accessible within this class
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
     * 5. GET ALL EXPENSES
     * 5.1 Fetches every row from the expenses table.
     * 5.2 Sorted by ID descending (newest first).
     * 5.3 Returns a MySQLi result object (or false on failure).
     */
    public function getAll() {
        return $this->db->query("SELECT * FROM expenses ORDER BY id DESC");
    }

    /**
     * 6. ADD A NEW EXPENSE OR INCOME
     * 6.1 Inserts a new row into the expenses table.
     * 6.2 Uses a prepared statement with 'sdss' binding:
     *     s = string (title), d = double (amount), s = string (category), s = string (type).
     * 6.3 Returns true on success, false on failure.
     */
    public function add($title, $amount, $category, $type = 'expense') {
        $stmt = $this->db->prepare("INSERT INTO expenses (title, amount, category, type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $title, $amount, $category, $type);
        return $stmt->execute();
    }

    /**
     * 7. FIND A SINGLE EXPENSE BY ID
     * 7.1 Used to populate the edit form/modal.
     * 7.2 Returns an associative array of the expense data, or null if not found.
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM expenses WHERE id = ?");
        // 7.3 'i' = integer binding for the ID parameter
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * 8. UPDATE AN EXISTING EXPENSE OR INCOME
     * 8.1 Modifies the title, amount, category, and type for a given expense ID.
     * 8.2 Uses prepared statement with 'sdssi' binding.
     * 8.3 Returns true on success, false on failure.
     */
    public function update($id, $title, $amount, $category, $type = 'expense') {
        $stmt = $this->db->prepare("UPDATE expenses SET title = ?, amount = ?, category = ?, type = ? WHERE id = ?");
        $stmt->bind_param("sdssi", $title, $amount, $category, $type, $id);
        return $stmt->execute();
    }

    /**
     * 9. DELETE AN EXPENSE
     * 9.1 Removes an expense row by its ID.
     * 9.2 Returns true on success, false on failure.
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM expenses WHERE id = ?");
        // 9.3 'i' = integer binding for the ID
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}