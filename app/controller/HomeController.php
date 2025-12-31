<?php

echo "<pre>";
echo __DIR__ . "\n";
echo file_exists(__DIR__ . '/../model/Expense.php') ? "FOUND Expense.php" : "NOT FOUND Expense.php";
exit;
