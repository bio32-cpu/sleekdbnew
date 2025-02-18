<?php
require 'vendor/autoload.php';
use SleekDB\Store;

$dataDir = "database";
$usersStore = new Store("users", $dataDir, ["timeout" => false]);

if (isset($_GET['ping'])) {
    echo json_encode(["status" => "ok"]);
    exit();
}

if (isset($_GET['get_users'])) {
    header('Content-Type: application/json');
    echo json_encode($usersStore->findAll());
    exit();
}

header('Content-Type: application/json');
echo json_encode(["error" => "Invalid request"]);
exit();
?>