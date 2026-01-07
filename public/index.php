<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../app/controller/HomeController.php";
$controller = new HomeController();
$action = $_GET['action'] ?? 'index';

// Unified Router
if ($action === 'ajaxAdd') {
    $controller->ajaxAdd();
} elseif ($action === 'edit') {
    $controller->edit();
} elseif ($action === 'update') {
    $controller->update();
} elseif ($action === 'delete') {
    $controller->delete();
} else {
    $controller->index();
}