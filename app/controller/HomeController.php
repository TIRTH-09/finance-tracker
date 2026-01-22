<?php
require_once __DIR__ . "/../model/Expense.php";
require_once __DIR__ . "/../model/User.php";

class HomeController {
    
    // ... [Keep checkAuth(), index(), login(), logout() as they were] ...

    // START: KEEP PREVIOUS METHODS
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
    }

    public function index() {
        $this->checkAuth();
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

    public function login() {
        if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
        require __DIR__ . "/../view/login.php";
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
    
    public function auth() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $user = $userModel->login($_POST['username'], $_POST['password']);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid username or password";
                $username_value = $_POST['username']; // Send back input
                require __DIR__ . "/../view/login.php";
            }
        }
    }
    // END: KEEP PREVIOUS METHODS


    // --- NEW: REGISTRATION LOGIC ---
    
    public function register() {
        if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
        require __DIR__ . "/../view/register.php";
    }

    public function storeUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            // Server Validation
            if ($password !== $confirm) {
                $error = "Passwords do not match";
                require __DIR__ . "/../view/register.php";
                exit;
            }

            $userModel = new User();
            $status = $userModel->register($username, $password);

            if ($status === "success") {
                // Auto login or redirect to login
                header("Location: index.php?action=login&registered=true");
                exit;
            } elseif ($status === "exists") {
                $error = "Username already taken";
                require __DIR__ . "/../view/register.php";
            } else {
                $error = "Database error. Try again.";
                require __DIR__ . "/../view/register.php";
            }
        }
    }

    // ... [Keep all your AJAX methods (ajaxAdd, etc.) exactly as they were] ...
    
    // Helper to get total (Required for AJAX)
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
        $this->checkAuth();
        ob_clean();
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);
            $category = $_POST['category'] ?? 'Other';
            
            if (empty($title) || $amount <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid input']); exit;
            }

            $model = new Expense();
            $success = $model->add($title, $amount, $category);
            
            if ($success) {
                $result = $model->getAll(); 
                $newItem = $result->fetch_assoc(); 
                $dateStr = isset($newItem['created_at']) ? date('M d, h:i A', strtotime($newItem['created_at'])) : date('M d, h:i A');
                $newItem['formatted_date'] = $dateStr;
                echo json_encode(['success' => true, 'newTotal' => $this->getCurrentTotal(), 'expense' => $newItem]);
            } else {
                echo json_encode(['success' => false, 'message' => 'DB Error']);
            }
            exit;
        }
    }
    
    // (Ensure you keep ajaxDelete, ajaxUpdate, ajaxGetExpense here too)
    // ... [Paste them from previous code] ...
}