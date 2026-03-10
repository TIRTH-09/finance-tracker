
<?php
/**
 * ============================================================
 * HOME CONTROLLER — HomeController.php
 * ============================================================
 * 1. This is the main controller for the Finance Tracker application.
 * 1.1 It handles ALL actions: page rendering, authentication, and AJAX API calls.
 * 1.2 Follows a simple MVC pattern: Controller receives requests,
 *     uses Models for data, and loads Views for HTML output.
 * ============================================================
 */

// 2. LOAD MODELS
// 2.1 Include the Expense model (handles CRUD for expenses table)
require_once __DIR__ . "/../model/Expense.php";
// 2.2 Include the User model (handles login, registration, user lookup)
require_once __DIR__ . "/../model/User.php";

class HomeController {
    
    /**
     * 3. AUTHENTICATION GUARD
     * 3.1 Private helper method called at the start of protected pages/actions.
     * 3.2 If user_id is NOT in session, redirect to login page and stop execution.
     */
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
    }

    /**
     * 4. HOME / OVERVIEW PAGE
     * 4.1 Displays the main dashboard with total spending, add form, and recent transactions.
     * 4.2 Protected — requires login.
     */
    public function index() {
        // 4.3 Check if user is authenticated first
        $this->checkAuth();

        // 4.4 Create Expense model and fetch all expenses from database
        $model = new Expense();
        $result = $model->getAll();

        // 4.5 Build an array of expenses and calculate totals
        $expenses = [];
        $incomeTotal = 0;
        $expenseTotal = 0;
        
        if ($result) {
            // 4.6 Loop through each row from the database result
            while ($row = $result->fetch_assoc()) {
                $expenses[] = $row;
                if (($row['type'] ?? 'expense') === 'income') {
                    $incomeTotal += (float)$row['amount'];
                } else {
                    $expenseTotal += (float)$row['amount'];
                }
            }
        }
        // 4.7 Net balance = income - expenses
        $netBalance = $incomeTotal - $expenseTotal;
        $total = $netBalance;

        // 4.8 Load the home view
        require __DIR__ . "/../view/home.php";
    }

    /**
     * 5. TRANSACTIONS PAGE
     * 5.1 Displays all transactions in a searchable list.
     * 5.2 Protected — requires login.
     */
    public function transactions() {
        // 5.3 Verify authentication
        $this->checkAuth();

        // 5.4 Fetch all expenses from database
        $model = new Expense();
        $result = $model->getAll();

        // 5.5 Build array of expense rows
        $expenses = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $expenses[] = $row;
            }
        }

        // 5.6 Load the transactions view
        require __DIR__ . "/../view/transactions.php";
    }

    /**
     * 6. SHOW LOGIN PAGE
     * 6.1 Renders the login form.
     * 6.2 Redirects to dashboard if user is already logged in.
     */
    public function login() {
        // 6.3 If already logged in, skip login and go to dashboard
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }
        // 6.4 Load the login view
        require __DIR__ . "/../view/login.php";
    }

    /**
     * 7. PROCESS LOGIN (Authentication)
     * 7.1 Handles POST submission from the login form.
     * 7.2 Validates credentials and either logs in or shows field-specific errors.
     */
    public function auth() {
        // 7.3 Only accept POST requests — redirect GET requests back to login
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=login");
            exit;
        }

        // 7.4 Extract and sanitize form data
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // 7.5 Initialize field-specific error messages
        $usernameError = '';
        $passwordError = '';

        // 7.6 VALIDATION STEP 1: Check for empty fields
        if ($username === '') {
            $usernameError = 'Username is required.';
        }
        if ($password === '') {
            $passwordError = 'Password is required.';
        }

        // 7.7 If any field is empty, re-render login page with errors
        if ($usernameError || $passwordError) {
            require __DIR__ . "/../view/login.php";
            return;
        }

        // 7.8 VALIDATION STEP 2: Check if username exists in database
        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (!$user) {
            // 7.8.1 Username not found — show error on username field only
            $usernameError = 'Username not found.';
            require __DIR__ . "/../view/login.php";
            return;
        }

        // 7.9 VALIDATION STEP 3: Verify password against stored hash
        if (!password_verify($password, $user['password'])) {
            // 7.9.1 Password wrong — show error on password field only
            $passwordError = 'Incorrect password.';
            require __DIR__ . "/../view/login.php";
            return;
        }

        // 7.10 SUCCESS: Store user info in session and redirect to dashboard
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    }

    /**
     * 8. SHOW REGISTRATION PAGE
     * 8.1 Renders the registration form for new users.
     * 8.2 Redirects to dashboard if user is already logged in.
     */
    public function register() {
        // 8.3 Redirect to dashboard if already logged in
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }
        // 8.4 Load the registration view
        require __DIR__ . "/../view/register.php";
    }

    /**
     * 9. PROCESS REGISTRATION (Create new user)
     * 9.1 Handles POST submission from the registration form.
     * 9.2 Validates inputs, checks for duplicate usernames, creates user.
     */
    public function storeUser() {
        // 9.3 Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=register");
            exit;
        }

        // 9.4 Extract form data
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        // 9.5 Validate: all fields must be filled
        if ($username === '' || $password === '' || $confirm === '') {
            $error = "All fields are required.";
            require __DIR__ . "/../view/register.php";
            return;
        }

        // 9.6 Validate: passwords must match
        if ($password !== $confirm) {
            $error = "Passwords do not match.";
            require __DIR__ . "/../view/register.php";
            return;
        }

        // 9.7 Attempt to register via the User model
        $userModel = new User();
        $result = $userModel->register($username, $password);

        // 9.8 Handle duplicate username
        if ($result === 'exists') {
            $error = "Username is already taken.";
            require __DIR__ . "/../view/register.php";
            return;
        }

        // 9.9 Registration successful — set flash message and redirect to login
        if ($result === 'success') {
            $_SESSION['register_success'] = "Account created successfully! Please sign in.";
            header("Location: index.php?action=login");
            exit;
        }

        // 9.10 Fallback error for unexpected failures
        $error = "Something went wrong. Please try again.";
        require __DIR__ . "/../view/register.php";
    }

    /**
     * 10. LOGOUT
     * 10.1 Destroys the session and redirects to login.
     */
    public function logout() {
        // 10.2 Destroy all session data
        session_destroy();
        // 10.3 Redirect to login page
        header("Location: index.php?action=login");
        exit;
    }

    // ============================================================
    // 11. AJAX API METHODS
    // 11.1 These methods return JSON responses (not HTML pages).
    // 11.2 Called by JavaScript fetch() from the frontend.
    // ============================================================

    /**
     * 11.3 HELPER: Calculate the current total of all expenses.
     * 11.3.1 Used after add/delete/update to return the new total to the frontend.
     */
    private function getCurrentTotals() {
        $model = new Expense();
        $result = $model->getAll();
        $incomeTotal = 0;
        $expenseTotal = 0;
        while ($row = $result->fetch_assoc()) {
            if (($row['type'] ?? 'expense') === 'income') {
                $incomeTotal += (float)$row['amount'];
            } else {
                $expenseTotal += (float)$row['amount'];
            }
        }
        return [
            'incomeTotal' => $incomeTotal,
            'expenseTotal' => $expenseTotal,
            'netBalance' => $incomeTotal - $expenseTotal
        ];
    }

    /**
     * 12. AJAX ADD EXPENSE
     * 12.1 Receives POST data from the expense form.
     * 12.2 Validates inputs, adds to DB, returns JSON with new expense data.
     */
    public function ajaxAdd() {
        // 12.3 Protect this endpoint
        $this->checkAuth();
        // 12.4 Clear any buffered output and set JSON header
        $this->cleanOutput();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 12.5 Extract and sanitize form inputs
            $title = trim($_POST['title'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);
            // 12.5.1 Whitelist-validate the category value
            $category = in_array($_POST['category'] ?? '', ['Food', 'Shopping', 'Transport', 'Bills', 'Other'])
                ? $_POST['category'] : 'Other';
            // 12.5.2 Validate type (income or expense)
            $type = in_array($_POST['type'] ?? '', ['income', 'expense']) ? $_POST['type'] : 'expense';

            // 12.6 Server-side validation
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

            // 12.7 Insert into database via Expense model
            $model = new Expense();
            $success = $model->add($title, $amount, $category, $type);
            
            if ($success) {
                $result = $model->getAll(); 
                $newItem = $result->fetch_assoc(); 
                $dateStr = isset($newItem['created_at']) ? date('M d, h:i A', strtotime($newItem['created_at'])) : date('M d, h:i A');
                $newItem['formatted_date'] = $dateStr;
                $totals = $this->getCurrentTotals();
                echo json_encode(['success' => true, 'totals' => $totals, 'expense' => $newItem]);
            } else {
                echo json_encode(['success' => false, 'message' => 'DB Error']);
            }
            exit;
        }
    }

    /**
     * 13. AJAX DELETE EXPENSE
     * 13.1 Receives POST with expense ID, deletes it from DB.
     * 13.2 Returns JSON with success status and updated total.
     */
    public function ajaxDelete() {
        // 13.3 Protect and set JSON output
        $this->checkAuth();
        $this->cleanOutput();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $model = new Expense();
                $success = $model->delete($id);
                $totals = $this->getCurrentTotals();
                echo json_encode(['success' => $success, 'totals' => $totals]);
            } else {
                echo json_encode(['success' => false, 'message' => 'ID missing']);
            }
            exit;
        }
    }

    /**
     * 14. AJAX UPDATE EXPENSE
     * 14.1 Receives POST with expense data, updates the existing record.
     * 14.2 Returns JSON with success status and updated total.
     */
    public function ajaxUpdate() {
        // 14.3 Protect and set JSON output
        $this->checkAuth();
        $this->cleanOutput();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
            $title = trim($_POST['title'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);
            $category = in_array($_POST['category'] ?? '', ['Food', 'Shopping', 'Transport', 'Bills', 'Other'])
                ? $_POST['category'] : 'Other';
            $type = in_array($_POST['type'] ?? '', ['income', 'expense']) ? $_POST['type'] : 'expense';

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
            $success = $model->update($id, $title, $amount, $category, $type);
            $totals = $this->getCurrentTotals();
            echo json_encode(['success' => $success, 'totals' => $totals]);
            exit;
        }
    }

    /**
     * 15. AJAX GET SINGLE EXPENSE
     * 15.1 Fetches one expense by ID (used to populate the edit modal).
     * 15.2 Returns JSON with the expense data.
     */
    public function ajaxGetExpense() {
        // 15.3 Protect and set JSON output
        $this->checkAuth();
        $this->cleanOutput();

        // 15.4 Read the expense ID from the query string
        $id = $_GET['id'] ?? null;
        if ($id) {
            // 15.5 Look up the expense via the model
            $model = new Expense();
            $data = $model->find($id);
            // 15.6 Return the data (or success=false if not found)
            echo json_encode(['success' => !!$data, 'data' => $data]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    /**
     * 16. HELPER: Clean output buffer and set JSON content type
     * 16.1 Called before all AJAX methods to ensure clean JSON output.
     * 16.2 ob_clean() removes any HTML that may have been buffered.
     */
    private function cleanOutput() {
        ob_clean();
        header('Content-Type: application/json');
    }
}
