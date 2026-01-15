<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../app/controller/HomeController.php";
$controller = new HomeController();

// Get the action from URL, default to 'index'
$action = $_GET['action'] ?? 'index';

// Unified Router - Routes requests to Controller methods
switch ($action) {
    case 'ajaxAdd':
        $controller->ajaxAdd();
        break;
    case 'ajaxDelete':      // NEW
        $controller->ajaxDelete();
        break;
    case 'ajaxGetExpense':  // NEW
        $controller->ajaxGetExpense();
        break;
    case 'ajaxUpdate':      // NEW
        $controller->ajaxUpdate();
        break;
    case 'index':
    default:
        $controller->index();
        break;
}