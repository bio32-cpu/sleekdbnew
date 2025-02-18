<?php
require 'vendor/autoload.php';

use SleekDB\Store;

// Định nghĩa thư mục chứa dữ liệu
$dataDir = "database";
$usersStore = new Store("users", $dataDir, ["timeout" => false]);

// Kiểm tra xem người dùng "Alice" đã tồn tại chưa
$existingUser = $usersStore->findOneBy(["email", "=", "alice@example.com"]);

if (!$existingUser) {
    // Nếu chưa có thì thêm mới
    $usersStore->insert([
        "name" => "Alice",
        "email" => "alice@example.com",
        "age" => 25
    ]);
} else {
    echo "Người dùng Alice đã tồn tại, không thêm trùng!\n";
}

// Đọc dữ liệu hiện có
$allUsers = $usersStore->findAll();
print_r($allUsers);
?>