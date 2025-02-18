<?php
require 'vendor/autoload.php';
use SleekDB\Store;
$mainServer = "http://localhost/SleekDB/api.php?get_users=1"; 
$response = @file_get_contents($mainServer . "?get_users=1");
$allUsers = json_decode($response, true) ?? [];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Replication - Đồng bộ dữ liệu</title>
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

    .btn {
        text-decoration: none;
        padding: 8px 12px;
        color: white;
        border-radius: 5px;
        display: inline-block;
        font-size: 14px;
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
    </style>
</head>

<body>

    <h1>📋 Danh sách Người Dùng (Replication)</h1>

    <a class="btn add-user" href="insert.php">➕ Thêm người dùng</a>

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
            <td colspan="5">📭 Không có dữ liệu hoặc không thể lấy dữ liệu từ server chính.</td>
        </tr>
        <?php else: ?>
        <?php foreach ($allUsers as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user["_id"] ?? "N/A") ?></td>
            <td><?= htmlspecialchars($user["name"] ?? "Không có dữ liệu") ?></td>
            <td><?= htmlspecialchars($user["email"] ?? "Không có dữ liệu") ?></td>
            <td><?= htmlspecialchars($user["age"] ?? "Không có dữ liệu") ?></td>
            <td>
                <a class="btn edit" href="edit.php?id=<?= urlencode($user['_id']) ?>">✏️ Sửa</a>
                <a class="btn delete" href="delete.php?id=<?= urlencode($user['_id']) ?>"
                    onclick="return confirm('Bạn có chắc muốn xóa?')">🗑️ Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </table>

</body>

</html>