<?php
require 'vendor/autoload.php';
require 'functions.php';
use SleekDB\Store;

$dataDirMain = "database";
$dataDirReplica = "database-replica";

$usersStoreMain = new Store("users", $dataDirMain, ["timeout" => false]);
$usersStoreReplica = new Store("users", $dataDirReplica, ["timeout" => false]);

// Kiểm tra nếu có ID cần xóa
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?message=Lỗi: ID không hợp lệ!");
    exit();
}

$id = intval($_GET['id']);

// Kiểm tra xem người dùng có tồn tại trong database chính không
$userExistsMain = $usersStoreMain->findBy(["_id", "=", $id]);
if (empty($userExistsMain)) {
    header("Location: index.php?message=Lỗi: Người dùng không tồn tại trong database chính!");
    exit();
}

// Xóa dữ liệu từ database chính
$usersStoreMain->deleteBy(["_id", "=", $id]);

// Kiểm tra xem người dùng có tồn tại trong database replica không
$userExistsReplica = $usersStoreReplica->findBy(["_id", "=", $id]);
if (!empty($userExistsReplica)) {
    $usersStoreReplica->deleteBy(["_id", "=", $id]); // Xóa dữ liệu từ replica nếu tồn tại
}

// Điều hướng về trang chính sau khi xóa thành công
header("Location: index.php?message=Xóa thành công trên cả hai database!");
exit();
?>