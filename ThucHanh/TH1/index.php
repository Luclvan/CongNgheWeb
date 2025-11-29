<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/* ================== MẢNG DỮ LIỆU HOA ================== */
$flowers = [
    [
        'name' => 'Hoa hồng',
        'description' => 'Hoa hồng là biểu tượng của tình yêu, có nhiều màu sắc khác nhau.',
        'image' => 'doquyen.jpg'
    ],
    [
        'name' => 'Hoa tulip',
        'description' => 'Tulip mang vẻ đẹp thanh lịch, thường nở rộ vào mùa xuân.',
        'image' => 'haiduong.jpg'
    ],
    [
        'name' => 'Hoa lan',
        'description' => 'Hoa lan có hình dáng đa dạng, sang trọng.',
        'image' => 'mai.jpg'
    ],
    [
        'name' => 'Hoa cúc',
        'description' => 'Hoa cúc tượng trưng cho sự trường thọ và bình an.',
        'image' => 'tuongvy.jpg'
    ],

];

/* ================== XÁC ĐỊNH ROLE ================== */
$role = isset($_GET['role']) ? $_GET['role'] : 'guest';
$isAdmin = ($role === 'admin');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hiển thị ảnh hoa</title>
    <style>
        body {
            font-family: Arial;
            max-width: 1000px;
            margin: auto;
            padding: 20px;
        }

        .nav a {
            margin-right: 10px;
            padding: 6px 12px;
            border: 1px solid #333;
            text-decoration: none;
        }

        .nav .active {
            background: #333;
            color: white;
        }

        /* ===== GIAO DIỆN KHÁCH ===== */
        .flower-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .flower-item {
            width: 48%;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .flower-item img {
            width: 100%;
            height: auto;
        }

        /* ===== GIAO DIỆN ADMIN ===== */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px;
        }

        table img {
            width: 120px;
        }
    </style>
</head>
<body>

<div class="nav">
    <a href="?role=guest" class="<?= !$isAdmin ? 'active' : '' ?>">Người dùng khách</a>
    <a href="?role=admin" class="<?= $isAdmin ? 'active' : '' ?>">Quản trị</a>
</div>

<?php if (!$isAdmin): ?>
    <!-- ================== GIAO DIỆN KHÁCH ================== -->
    <h1>Danh sách các loài hoa</h1>
    <div class="flower-list">
        <?php foreach ($flowers as $flower): ?>
            <div class="flower-item">
                <img src="image/<?= $flower['image'] ?>">
                <h3><?= $flower['name'] ?></h3>
                <p><?= $flower['description'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>

<?php else: ?>
    <!-- ================== GIAO DIỆN ADMIN ================== -->
    <h1>Quản lý hoa (CRUD)</h1>

    <table>
        <tr>
            <th>STT</th>
            <th>Tên hoa</th>
            <th>Mô tả</th>
            <th>Ảnh</th>
            <th>Thao tác</th>
        </tr>

        <?php foreach ($flowers as $i => $flower): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $flower['name'] ?></td>
                <td><?= $flower['description'] ?></td>
                <td>
                    <img src="image/<?= $flower['image'] ?>">
                </td>
                <td>
                    <a href="#">Sửa</a> |
                    <a href="#" onclick="return confirm('Xóa không?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php endif; ?>

</body>
</html>
