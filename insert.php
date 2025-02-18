<?php
require 'vendor/autoload.php';
require 'functions.php';
use SleekDB\Store;

// Define paths for both databases
$dataDirs = ["database", "database-replica"];

// Initialize stores for both databases
$usersStoreMain = new Store("users", $dataDirs[0], ["timeout" => false]);
$usersStoreReplica = new Store("users", $dataDirs[1], ["timeout" => false]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = [
        "name" => $_POST["name"],
        "email" => $_POST["email"],
        "age" => intval($_POST["age"])
    ];

    // Insert user into both databases
    $usersStoreMain->insert($user);
    $usersStoreReplica->insert($user);

    header("Location: index.php?message=Thêm thành công!");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm Người Dùng</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        background-color: #f4f4f4;
    }

    form {
        background: white;
        padding: 20px;
        width: 50%;
        margin: auto;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    input,
    button {
        padding: 10px;
        width: 90%;
        margin-bottom: 10px;
    }

    button {
        background: green;
        color: white;
        border: none;
        cursor: pointer;
    }

    a {
        display: inline-block;
        margin-top: 10px;
        text-decoration: none;
        color: blue;
    }
    </style>
</head>

<body>
    <h1>Thêm Người Dùng</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Tên" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="number" name="age" placeholder="Tuổi" required>
        <button type="submit">Thêm</button>
    </form>
    <a href="index.php">Quay lại danh sách</a>
</body>

</html>