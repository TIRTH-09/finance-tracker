<?php
require_once __DIR__ . "/../model/Expense.php";

class HomeController {
    
    // Show Dashboard
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

    // 1. AJAX Add
    public function ajaxAdd() {
        $this->cleanOutput(); // Helper to clear buffer
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);
            $category = $_POST['category'] ?? 'Other';
            
            // VALIDATION
            if (empty($title)) {
                echo json_encode(['success' => false, 'message' => 'Description is required']);
                exit;
            }
            if ($amount <= 0) {
                echo json_encode(['success' => false, 'message' => 'Amount must be greater than 0']);
                exit;
            }

            $model = new Expense();
            $success = $model->add($title, $amount, $category);
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    // 2. AJAX Delete
    public function ajaxDelete() {
        $this->cleanOutput();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $model = new Expense();
                $success = $model->delete($id);
                echo json_encode(['success' => $success]);
            } else {
                echo json_encode(['success' => false, 'message' => 'ID missing']);
            }
            exit;
        }
    }

    // 3. AJAX Get Single (For Edit Modal)
    public function ajaxGetExpense() {
        $this->cleanOutput();

        $id = $_GET['id'] ?? null;
        if ($id) {
            $model = new Expense();
            $data = $model->find($id);
            if ($data) {
                echo json_encode(['success' => true, 'data' => $data]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No ID']);
        }
        exit;
    }

    // 4. AJAX Update
    public function ajaxUpdate() {
        $this->cleanOutput();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $title = trim($_POST['title'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);
            $category = $_POST['category'] ?? 'Other';

            // VALIDATION
            if (empty($title) || $amount <= 0 || !$id) {
                echo json_encode(['success' => false, 'message' => 'Invalid input']);
                exit;
            }

            $model = new Expense();
            $success = $model->update($id, $title, $amount, $category);
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    // Helper to prevent JSON errors
    private function cleanOutput() {
        ob_clean();
        header('Content-Type: application/json');
    }
}