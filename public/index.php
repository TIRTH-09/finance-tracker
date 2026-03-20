<?php
/**
 * ============================================================
 * FRONT CONTROLLER / ROUTER — index.php
 * ============================================================
 * 1. This is the single entry point for the entire application.
 * 1.1 ALL requests go through this file (e.g. index.php?action=login).
 * 1.2 It reads the 'action' query parameter and calls the appropriate
 *     controller method to handle the request.
 * ============================================================
 */

// 2. SESSION & ERROR REPORTING SETUP
// 2.1 Start the PHP session — used to track logged-in users across pages
session_start();

// 2.2 Enable full error reporting (helpful during development, disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 3. LOAD THE CONTROLLER
// 3.1 Include the HomeController class which contains all the action methods
require_once "../app/controller/HomeController.php";

// 3.2 Create an instance of the controller to call its methods
$controller = new HomeController();

// 4. ROUTE THE REQUEST
// 4.1 Read the 'action' parameter from the URL query string
// 4.2 Default to 'index' if no action is specified (shows the dashboard)
$action = $_GET['action'] ?? 'index';

// 4.3 Use a switch statement to map each action string to a controller method
switch ($action) {

    // 5. AUTHENTICATION ROUTES
    // 5.1 Show the login form page
    case 'login':
        $controller->login();
        break;

    // 5.2 Process login form submission (POST) — validates credentials
    case 'auth':
        $controller->auth();
        break;

    // 5.3 Show the registration form page
    case 'register':
        $controller->register();
        break;

    // 5.4 Process registration form submission (POST) — creates new user
    case 'storeUser':
        $controller->storeUser();
        break;

    // 5.5 Destroy session and redirect to login
    case 'logout':
        $controller->logout();
        break;

    // 6. AJAX API ROUTES (called by JavaScript, return JSON)
    // 6.1 Add a new expense via AJAX POST
    case 'ajaxAdd':
        $controller->ajaxAdd();
        break;

    // 6.2 Delete an expense via AJAX POST
    case 'ajaxDelete':
        $controller->ajaxDelete();
        break;

    // 6.3 Get a single expense's data for the edit modal (AJAX GET)
    case 'ajaxGetExpense':
        $controller->ajaxGetExpense();
        break;

    // 6.4 Update an existing expense via AJAX POST
    case 'ajaxUpdate':
        $controller->ajaxUpdate();
        break;

    // 7. PAGE ROUTES
    // 7.1 Show the transactions list page
    case 'transactions':
        $controller->transactions();
        break;

    // 7.2 Export transactions report as CSV
    case 'exportExcel':
        $controller->exportExcel();
        break;

    // 7.3 Show investment suggestions page
    case 'invest':
        $controller->invest();
        break;

    // 7.4 Default route — show the home/overview dashboard
    case 'index':
    default:
        $controller->index();
        break;
}