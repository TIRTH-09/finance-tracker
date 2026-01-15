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

    // Fix: Return raw float, do not format as string here
    private function getCurrentTotal() {
        $model = new Expense();
        $result = $model->getAll();
        $total = 0;
        while ($row = $result->fetch_assoc()) {
            $total += (float)$row['amount'];
        }
        return $total; 
    }

    public function ajaxAdd() {
        $this->cleanOutput();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);
            $category = $_POST['category'] ?? 'Other';
            
            if (empty($title) || $amount <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid input']);
                exit;
            }

            $model = new Expense();
            $success = $model->add($title, $amount, $category);
            
            if ($success) {
                // Fetch the item we just created to get its ID and Time
                $result = $model->getAll(); 
                $newItem = $result->fetch_assoc(); 
                
                // Add a formatted date string for JS to use
                $dateStr = isset($newItem['created_at']) 
                           ? date('M d, h:i A', strtotime($newItem['created_at'])) 
                           : date('M d, h:i A');
                $newItem['formatted_date'] = $dateStr;

                echo json_encode([
                    'success' => true, 
                    'newTotal' => $this->getCurrentTotal(), // Sends raw number
                    'expense' => $newItem
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'DB Error']);
            }
            exit;
        }
    }

    public function ajaxDelete() {
        $this->cleanOutput();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $model = new Expense();
                $success = $model->delete($id);
                echo json_encode([
                    'success' => $success,
                    'newTotal' => $this->getCurrentTotal()
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'ID missing']);
            }
            exit;
        }
    }

    public function ajaxUpdate() {
        $this->cleanOutput();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $title = trim($_POST['title'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);
            $category = $_POST['category'] ?? 'Other';

            if (empty($title) || $amount <= 0 || !$id) {
                echo json_encode(['success' => false, 'message' => 'Invalid input']);
                exit;
            }

            $model = new Expense();
            $success = $model->update($id, $title, $amount, $category);
            echo json_encode([
                'success' => $success,
                'newTotal' => $this->getCurrentTotal()
            ]);
            exit;
        }
    }

    public function ajaxGetExpense() {
        $this->cleanOutput();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $model = new Expense();
            $data = $model->find($id);
            echo json_encode(['success' => !!$data, 'data' => $data]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    private function cleanOutput() {
        ob_clean();
        header('Content-Type: application/json');
    }
}