<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Tên file CSV (đặt cùng thư mục với file PHP này)
$filename = '65HTTT_Danh_sach_diem_danh.csv';

// Kiểm tra file tồn tại
if (!file_exists($filename)) {
    die("Không tìm thấy tệp: $filename");
}

$rows = [];

// Đọc file CSV
if (($handle = fopen($filename, 'r')) !== false) {
    // Nếu file dùng dấu ; thay vì , thì đổi tham số thứ 4 của fgetcsv thành ';'
    while (($data = fgetcsv($handle, 0, ",")) !== false) {
        $rows[] = $data;
    }
    fclose($handle);
}

// Tách header và các dòng dữ liệu
$header   = $rows[0] ?? [];
$dataRows = array_slice($rows, 1);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách điểm danh 65HTTT</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        caption {
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 20px;
        }
    </style>
</head>
<body>

<table>
    <caption>Danh sách điểm danh lớp 65HTTT</caption>
    <thead>
    <tr>
        <?php foreach ($header as $col): ?>
            <th><?= htmlspecialchars($col) ?></th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($dataRows as $row): ?>
        <tr>
            <?php foreach ($row as $cell): ?>
                <td><?= htmlspecialchars($cell) ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
