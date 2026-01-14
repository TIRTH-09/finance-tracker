<?php
require_once __DIR__ . "/../model/Expense.php";

class HomeController {
    public function index() {
        $model = new Expense();
        $result = $model->getAll();
        $expenses = [];
        $total = 0;
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $expenses[] = $row;
                $total += (float)$row['amount'];
            }
        }
        require __DIR__ . "/../view/home.php";
    }

    public function ajaxAdd() {
        // Prevent any previous output from breaking JSON
        ob_clean(); 
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Expense();
            // Basic validation
            $title = $_POST['title'] ?? '';
            $amount = $_POST['amount'] ?? 0;
            $category = $_POST['category'] ?? 'Other';
            
            if(!empty($title) && !empty($amount)) {
                $success = $model->add($title, $amount, $category);
                echo json_encode(['success' => $success]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid input']);
            }
            exit;
        }
    }

    public function edit() {
        $model = new Expense();
        $expense = $model->find($_GET['id']);
        if (!$expense) { header("Location: index.php"); exit; }
        require __DIR__ . "/../view/edit.php";
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Expense();
            $model->update($_POST['id'], $_POST['title'], $_POST['amount'], $_POST['category']);
        }
        header("Location: index.php");
        exit;
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $model = new Expense();
            $model->delete($_GET['id']);
        }
        header("Location: index.php");
        exit;
    }
}