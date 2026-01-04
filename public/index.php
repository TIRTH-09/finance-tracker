<?php
require_once "../app/controller/HomeController.php";

$controller = new HomeController();
$action = $_GET["action"] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($action === "update") {
        $controller->update();
    } else {
        $controller->store();
    }
} else {
    if ($action === "delete") {
        $controller->delete();
    } elseif ($action === "edit") {
        $controller->edit();
    } else {
        $controller->index();
    }
}
