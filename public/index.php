<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/finance-tracker/app/controller/HomeController.php';

$controller = new HomeController();
$controller->index();
