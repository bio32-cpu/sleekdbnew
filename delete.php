<?php
require 'vendor/autoload.php';
require 'functions.php';
use SleekDB\Store;

$dataDir = "database";
$usersStore = new Store("users", $dataDir, ["timeout" => false]);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $usersStore->deleteBy(["_id", "=", $id]);
    replicateData("delete", ["_id" => $id]);

    header("Location: index.php?message=Xóa thành công!");
    exit();
}
?>