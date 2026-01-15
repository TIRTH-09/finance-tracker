<?php
// public/setup.php

$host = "127.0.0.1";
$user = "root";
$pass = "";

// 1. Connect to MySQL
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Database Connection Failed. Please ensure XAMPP is running.");
}

// 2. Logic: Check if Database & Users Table already exist
$dbCheck = $conn->query("SHOW DATABASES LIKE 'finance_tracker'");
$dbExists = $dbCheck->num_rows > 0;
$setupComplete = false;

if ($dbExists) {
    $conn->select_db("finance_tracker");
    $tableCheck = $conn->query("SHOW TABLES LIKE 'users'");
    if ($tableCheck->num_rows > 0) {
        $setupComplete = true;
    }
}

// 3. IF ALREADY INSTALLED (Your Laptop) -> Redirect Immediately
if ($setupComplete) {
    header("Location: index.php");
    exit();
}

// ====================================================
// IF NOT INSTALLED (Mentor's PC) -> Run Setup Silently
// ====================================================

// Create DB
$conn->query("CREATE DATABASE IF NOT EXISTS finance_tracker");
$conn->select_db("finance_tracker");

// Create Users Table
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Create Expenses Table
$conn->query("CREATE TABLE IF NOT EXISTS expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Create Default Admin (Password: password)
// INSERT IGNORE skips the error if admin already exists
$defaultPass = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; 
$conn->query("INSERT IGNORE INTO users (username, password) VALUES ('admin', '$defaultPass')");

$conn->close();

// 4. Setup Done -> Redirect Immediately to Login
header("Location: index.php");
exit();
?>