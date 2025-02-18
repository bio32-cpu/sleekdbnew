<?php
require 'vendor/autoload.php';
require 'functions.php';

$config = include("config.php");

// Chọn server nhanh nhất
$server = getFastestServer($config["db_servers"]);

// Kiểm tra xem server có hoạt động không
if (!$server) {
    die("Không có server nào khả dụng.");
}

// Gửi request đến server nhanh nhất để lấy dữ liệu
$response = @file_get_contents($server . "?get_users=1");
$allUsers = json_decode($response, true) ?? [];

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người Dùng</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 20px;
        padding: 20px;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: white;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }

    .edit,
    .delete,
    .add-user {
        text-decoration: none;
        padding: 8px 12px;
        color: white;
        border-radius: 5px;
        display: inline-block;
    }

    .edit {
        background-color: blue;
    }

    .delete {
        background-color: red;
    }

    .add-user {
        background-color: green;
        padding: 10px;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .message {
        color: green;
        font-weight: bold;
    }

    .server-info {
        font-weight: bold;
        color: blue;
        margin-bottom: 10px;
    }
    </style>
</head>

<body>

    <h1>Danh sách Người Dùng</h1>

    <!-- Hiển thị server API được chọn -->
    <p class="server-info">🔄 API Server được chọn: <strong><?= $server ?></strong></p>

    <a class="add-user" href="insert.php">➕ Thêm người dùng</a>

    <?php if (isset($_GET['message'])): ?>
    <p class="message"><?= htmlspecialchars($_GET['message']) ?></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Tuổi</th>
            <th>Hành động</th>
        </tr>
        <?php if (empty($allUsers)): ?>
        <tr>
            <td colspan="5">Không có dữ liệu.</td>
        </tr>
        <?php else: ?>
        <?php foreach ($allUsers as $user): ?>
        <tr>
            <td><?= isset($user["_id"]) ? $user["_id"] : "N/A" ?></td>
            <td><?= isset($user["name"]) ? $user["name"] : "Không có dữ liệu" ?></td>
            <td><?= isset($user["email"]) ? $user["email"] : "Không có dữ liệu" ?></td>
            <td><?= isset($user["age"]) ? $user["age"] : "Không có dữ liệu" ?></td>
            <td>
                <a class="edit" href="edit.php?id=<?= $user['_id'] ?>">✏️ Sửa</a>
                <a class="delete" href="delete.php?id=<?= $user['_id'] ?>"
                    onclick="return confirm('Bạn có chắc muốn xóa?')">🗑️ Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </table>

</body>

</html>