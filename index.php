<?php
require 'vendor/autoload.php';
require 'functions.php';

// Nạp cấu hình hệ thống
$config = include("config.php");

// Chọn server nhanh nhất từ danh sách
$server = getFastestServer($config["db_servers"]);

// Kiểm tra tính khả dụng của server
if (!$server) {
    die("⚠️ Không có server nào khả dụng.");
}

// Lấy dữ liệu người dùng từ API
$response = @file_get_contents($server . "?get_users=1");
$allUsers = json_decode($response, true) ?? [];

// Xử lý tìm kiếm
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($searchQuery) && !empty($allUsers)) {
    $filteredUsers = array_filter($allUsers, function ($user) use ($searchQuery) {
        // Tìm theo tên chính xác
        if (stripos($user["name"], $searchQuery) !== false) {
            return true;
        }
        // Tìm theo email chính xác
        if (stripos($user["email"], $searchQuery) !== false) {
            return true;
        }
        // Tìm theo tuổi chính xác
        if (isset($user["age"]) && $user["age"] == $searchQuery) {
            return true;
        }
        return false;
    });
} else {
    $filteredUsers = $allUsers; // Hiển thị tất cả nếu không có tìm kiếm
}
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

    .search-form {
        display: flex;
        justify-content: center;
        margin-bottom: 15px;
    }

    .search-input {
        width: 250px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px 0 0 5px;
        outline: none;
        font-size: 16px;
    }

    .search-button {
        padding: 10px 15px;
        border: 1px solid #4CAF50;
        background-color: #4CAF50;
        color: white;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
        font-size: 16px;
        transition: 0.3s;
    }

    .search-button:hover {
        background-color: #45a049;
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

    <h1>📋 Danh sách Người Dùng</h1>

    <!-- Hiển thị server API được chọn -->
    <p class="server-info">🔄 API Server được chọn: <strong><?= htmlspecialchars($server) ?></strong></p>

    <!-- Form tìm kiếm -->
    <form method="GET" action="" class="search-form">
        <input type="text" name="search" class="search-input" placeholder="🔍 Nhập tên, email hoặc tuổi..."
            value="<?= htmlspecialchars($searchQuery) ?>">
        <button type="submit" class="search-button">Tìm kiếm</button>
    </form>

    <a class="btn add-user" href="insert.php">➕ Thêm người dùng</a>

    <?php if (!empty($_GET['message'])): ?>
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

        <?php if (empty($filteredUsers)): ?>
        <tr>
            <td colspan="5">📭 Không có dữ liệu hoặc không tìm thấy kết quả.</td>
        </tr>
        <?php else: ?>
        <?php foreach ($filteredUsers as $user): ?>
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