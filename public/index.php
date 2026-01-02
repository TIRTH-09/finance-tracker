<?php
require_once "../app/controller/HomeController.php";

$controller = new HomeController();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $controller->store();
} else {
    $controller->index();
}
