<?php
require_once __DIR__ . '/Database.php';

class Expense {

    public static function getAll() {
        $db = Database::connect();
        return $db->query("SELECT * FROM expenses");
    }

    public static function add($title, $amount) {
        $db = Database::connect();
        $db->query("INSERT INTO expenses (title, amount) VALUES ('$title', '$amount')");
    }
}
