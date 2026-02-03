
<?php
require_once __DIR__ . "/../model/Expense.php";
require_once __DIR__ . "/../model/User.php"; // Include User Model

class HomeController {
    
    // Check if user is logged in
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
    }

    public function index() {
        $this->checkAuth(); // Protect this page

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

    /** Transactions page: all transactions list */
    public function transactions() {
        $this->checkAuth();

        $model = new Expense();
        $result = $model->getAll();
        $expenses = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $expenses[] = $row;
            }
        }
        require __DIR__ . "/../view/transactions.php";
    }

    // Show Login Page
    public function login() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }
        require __DIR__ . "/../view/login.php";
    }

    // Process Login Submission
    public function auth() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=login");
            exit;
        }
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = "Please enter both username and password.";
            require __DIR__ . "/../view/login.php";
            return;
        }

        $userModel = new User();
        $user = $userModel->login($username, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        }

        $error = "Invalid username or password.";
        require __DIR__ . "/../view/login.php";
    }

    // Logout
    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }

    // --- AJAX Methods (Keep these same, but protect them) ---
    
    // ... [Keep getCurrentTotal() here] ...
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
        $this->checkAuth(); // Protect
        // ... [Paste your previous ajaxAdd code here] ...
        $this->cleanOutput();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);
            $category = in_array($_POST['category'] ?? '', ['Food', 'Shopping', 'Transport', 'Bills', 'Other'])
                ? $_POST['category'] : 'Other';

            if ($title === '') {
                echo json_encode(['success' => false, 'message' => 'Description is required.']);
                exit;
            }
            if (strlen($title) > 255) {
                echo json_encode(['success' => false, 'message' => 'Description is too long.']);
                exit;
            }
            if ($amount <= 0 || $amount > 999999999.99) {
                echo json_encode(['success' => false, 'message' => 'Enter a valid amount (0.01 – 999999999.99).']);
                exit;
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

    public function ajaxDelete() {
        $this->checkAuth(); // Protect
        // ... [Paste previous ajaxDelete code] ...
        $this->cleanOutput();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $model = new Expense();
                $success = $model->delete($id);
                echo json_encode(['success' => $success, 'newTotal' => $this->getCurrentTotal()]);
            } else {
                echo json_encode(['success' => false, 'message' => 'ID missing']);
            }
            exit;
        }
    }

    public function ajaxUpdate() {
        $this->checkAuth(); // Protect
        // ... [Paste previous ajaxUpdate code] ...
        $this->cleanOutput();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
            $title = trim($_POST['title'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);
            $category = in_array($_POST['category'] ?? '', ['Food', 'Shopping', 'Transport', 'Bills', 'Other'])
                ? $_POST['category'] : 'Other';

            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                exit;
            }
            if ($title === '') {
                echo json_encode(['success' => false, 'message' => 'Description is required.']);
                exit;
            }
            if (strlen($title) > 255) {
                echo json_encode(['success' => false, 'message' => 'Description is too long.']);
                exit;
            }
            if ($amount <= 0 || $amount > 999999999.99) {
                echo json_encode(['success' => false, 'message' => 'Enter a valid amount (0.01 – 999999999.99).']);
                exit;
            }

            $model = new Expense();
            $success = $model->update($id, $title, $amount, $category);
            echo json_encode(['success' => $success, 'newTotal' => $this->getCurrentTotal()]);
            exit;
        }
    }

    public function ajaxGetExpense() {
        $this->checkAuth(); // Protect
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
//up
