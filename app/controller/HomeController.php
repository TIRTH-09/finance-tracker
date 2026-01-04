<?php
require_once __DIR__ . "/../model/Expense.php";

class HomeController
{
    public function index()
    {
        $expenseModel = new Expense();
        $result = $expenseModel->getAll();

        // ✅ Convert result to array ONCE
        $expenses = [];
        while ($row = $result->fetch_assoc()) {
            $expenses[] = $row;
        }

        // ✅ Calculate total safely
        $total = 0;
        foreach ($expenses as $row) {
            $total += $row['amount'];
        }

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

    public function delete()
    {
        if (isset($_GET["id"])) {
            $expense = new Expense();
            $expense->delete($_GET["id"]);
        }

        header("Location: /finance-tracker/public/");
        exit;
    }

    public function edit()
    {
        if (!isset($_GET["id"])) {
            header("Location: /finance-tracker/public/");
            exit;
        }

        $expenseModel = new Expense();
        $expense = $expenseModel->find($_GET["id"]);

        require __DIR__ . "/../view/edit.php";
    }

    public function update()
    {
        if (isset($_POST["id"], $_POST["title"], $_POST["amount"])) {
            $expense = new Expense();
            $expense->update($_POST["id"], $_POST["title"], $_POST["amount"]);
        }

        header("Location: /finance-tracker/public/");
        exit;
    }

    public function ajaxAdd()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../model/Expense.php';

        $expense = new Expense();
        $expense->create($_POST['title'], $_POST['amount']);

        echo json_encode(['success' => true]);
        exit;
    }
}

}
