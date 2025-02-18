<?php
require 'vendor/autoload.php';
require 'functions.php';
use SleekDB\Store;

$dataDir = "database";
$usersStore = new Store("users", $dataDir, ["timeout" => false]);

$id = intval($_GET['id']);
$user = $usersStore->findBy(["_id", "=", $id])[0] ?? null;

if (!$user) die("Người dùng không tồn tại!");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updatedData = [
        "_id" => $id,
        "name" => $_POST["name"],
        "email" => $_POST["email"],
        "age" => intval($_POST["age"])
    ];

    $usersStore->update($updatedData, ["_id", "=", $id]);
    replicateData("update", $updatedData);

    header("Location: index.php?message=Cập nhật thành công!");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa Người Dùng</title>
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
        background: blue;
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

    <h1>Chỉnh sửa Người Dùng</h1>
    <form method="POST">
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <input type="number" name="age" value="<?= htmlspecialchars($user['age']) ?>" required>
        <button type="submit">Cập nhật</button>
    </form>
    <a href="index.php">Quay lại danh sách</a>

</body>

</html>