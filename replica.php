<?php
require 'vendor/autoload.php';
use SleekDB\Store;

$dataDir = "database_replica";
$usersStore = new Store("users", $dataDir, ["timeout" => false]);

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["get_users"])) {
    header('Content-Type: application/json');
    echo json_encode($usersStore->findAll());
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Invalid request"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

switch ($data["action"]) {
    case "insert":
        $usersStore->insert($data["data"]);
        break;
    case "update":
        $usersStore->update($data["data"], ["_id", "=", $data["data"]["_id"]]);
        break;
    case "delete":
        $usersStore->deleteBy(["_id", "=", $data["data"]["_id"]]);
        break;
    default:
        header('Content-Type: application/json');
        echo json_encode(["error" => "Invalid action"]);
        exit();
}

header('Content-Type: application/json');
echo json_encode(["status" => "success"]);
?>