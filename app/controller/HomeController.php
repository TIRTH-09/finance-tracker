<?php
require_once __DIR__ . "/../model/Expense.php";

class HomeController
{
    public function index()
    {
        $expense = new Expense();
        $expenses = $expense->getAll();

        require __DIR__ . "/../view/home.php";
    }

    public function store()
    {
        if (isset($_POST["title"], $_POST["amount"])) {
            $expense = new Expense();
            $expense->add($_POST["title"], $_POST["amount"]);
        }

        header("Location: /finance-tracker/public/");
        exit;
    }
}
