<?php
session_start(); // START SESSION HERE

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../app/controller/HomeController.php";
$controller = new HomeController();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'login':
        $controller->login();
        break;
    case 'auth':
        $controller->auth();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'ajaxAdd':
        $controller->ajaxAdd();
        break;
    case 'ajaxDelete':
        $controller->ajaxDelete();
        break;
    case 'ajaxGetExpense':
        $controller->ajaxGetExpense();
        break;
    case 'ajaxUpdate':
        $controller->ajaxUpdate();
        break;
    case 'index':
    default:
        $controller->index();
        break;
}