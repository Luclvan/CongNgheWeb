<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>PHT Chương 2 - PHP Căn Bản</title>
</head>
<body>
<h1>Kết quả PHP Căn Bản</h1>

<?php
// TODO 1: Khai báo 3 biến
$ho_ten = "Lê Văn Lực"; 
$diem_tb = 9;           
$co_di_hoc_chuyen_can = true; 

// TODO 2: In ra thông tin sinh viên
echo "Họ tên: $ho_ten <br>";
echo "Điểm: $diem_tb <br>";

// TODO 3: Cấu trúc IF/ELSE IF/ELSE
if ($diem_tb >= 8.5 && $co_di_hoc_chuyen_can == true) {
    echo "Xếp loại: Giỏi <br>";
} elseif ($diem_tb >= 6.5 && $co_di_hoc_chuyen_can == true) {
    echo "Xếp loại: Khá <br>";
} elseif ($diem_tb >= 5.0 && $co_di_hoc_chuyen_can == true) {
    echo "Xếp loại: Trung bình <br>";
} else {
    echo "Xếp loại: Yếu (Cần cố gắng thêm!) <br>";
}

// TODO 4: Viết 1 hàm đơn giản
function chaoMung() {
    echo "Chúc mừng bạn đã hoàn thành PHT Chương 2!";
}

// TODO 5: Gọi hàm
chaoMung();
?>

</body>
</html>
