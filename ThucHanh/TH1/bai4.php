<?php
// Bật hiển thị lỗi
ini_set('display_errors', 1);
error_reporting(E_ALL);

/* ================== BIẾN CHUNG ================== */
$quizQuestions = [];
$quizError = '';

$csvHeader = [];
$csvRows   = [];
$csvError  = '';

/* ================== XỬ LÝ UPLOAD BÀI 2 (QUIZ TXT) ================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_quiz'])) {

    if (!isset($_FILES['quiz_file']) || $_FILES['quiz_file']['error'] !== UPLOAD_ERR_OK) {
        $quizError = "Có lỗi khi upload file đề trắc nghiệm.";
    } else {
        $tmpName = $_FILES['quiz_file']['tmp_name'];
        $content = file_get_contents($tmpName);

        // Mỗi câu hỏi cách nhau bởi 1 dòng trống
        $blocks = preg_split("/\n\s*\n/", trim($content));

        foreach ($blocks as $block) {
            // Định dạng: Câu hỏi + 4 đáp án + ANSWER:
            if (preg_match('/^(.*)\nA\.(.*)\nB\.(.*)\nC\.(.*)\nD\.(.*)\nANSWER:\s*(.*)$/s',
                           trim($block), $m)) {

                $quizQuestions[] = [
                    'question' => trim($m[1]),
                    'A' => trim($m[2]),
                    'B' => trim($m[3]),
                    'C' => trim($m[4]),
                    'D' => trim($m[5]),
                    'answer' => trim($m[6]) // có thể là "C" hoặc "C, D"
                ];
            }
        }

        if (empty($quizQuestions) && $quizError === '') {
            $quizError = "Không đọc được câu hỏi nào. Kiểm tra lại định dạng file .txt.";
        }
    }
}

/* ================== XỬ LÝ UPLOAD BÀI 3 (CSV ĐIỂM DANH) ================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_csv'])) {

    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        $csvError = "Có lỗi khi upload file CSV.";
    } else {
        $tmpName = $_FILES['csv_file']['tmp_name'];

        if (($handle = fopen($tmpName, 'r')) === false) {
            $csvError = "Không mở được file CSV.";
        } else {
            // Dòng đầu tiên: header
            if (($first = fgetcsv($handle, 0, ",")) !== false) {
                $csvHeader = $first;
            }

            // Các dòng còn lại
            while (($row = fgetcsv($handle, 0, ",")) !== false) {
                $csvRows[] = $row;
            }
            fclose($handle);

            if (empty($csvHeader)) {
                $csvError = "File CSV không có dữ liệu hoặc không đúng định dạng.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2 + Bài 3: Upload & hiển thị file</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1100px;
            margin: 20px auto;
        }
        h1 {
            margin-bottom: 5px;
        }
        h2 {
            margin-top: 30px;
        }
        form {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .quiz-question {
            border: 1px solid #999;
            padding: 8px;
            margin-bottom: 10px;
        }
        .quiz-answer {
            margin-top: 5px;
            font-style: italic;
            color: blue;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
    </style>
</head>
<body>

<h1>Bài 2 & Bài 3 – Upload và hiển thị nội dung tệp</h1>
<p>(Tiền đề cho việc lưu dữ liệu vào CSDL)</p>

<!-- ================== BÀI 2: QUIZ TXT ================== -->
<h2>Bài 2 – Upload file đề trắc nghiệm (.txt)</h2>
<form method="post" enctype="multipart/form-data">
    <label>Chọn file đề .txt:
        <input type="file" name="quiz_file" accept=".txt">
    </label>
    <button type="submit" name="upload_quiz">Upload & Hiển thị</button>
</form>

<?php if ($quizError): ?>
    <p class="error"><?= htmlspecialchars($quizError) ?></p>
<?php endif; ?>

<?php if (!empty($quizQuestions)): ?>
    <?php foreach ($quizQuestions as $index => $q): ?>
        <div class="quiz-question">
            <p><b>Câu <?= $index + 1 ?>:</b> <?= htmlspecialchars($q['question']) ?></p>
            <p>A. <?= htmlspecialchars($q['A']) ?></p>
            <p>B. <?= htmlspecialchars($q['B']) ?></p>
            <p>C. <?= htmlspecialchars($q['C']) ?></p>
            <p>D. <?= htmlspecialchars($q['D']) ?></p>
            <p class="quiz-answer">Đáp án đúng: <?= htmlspecialchars($q['answer']) ?></p>
        </div>
    <?php endforeach; ?>
<?php elseif (isset($_POST['upload_quiz']) && !$quizError): ?>
    <p>File không có câu hỏi nào.</p>
<?php endif; ?>

<hr>

<!-- ================== BÀI 3: CSV ĐIỂM DANH ================== -->
<h2>Bài 3 – Upload file danh sách điểm danh (.csv)</h2>
<form method="post" enctype="multipart/form-data">
    <label>Chọn file CSV:
        <input type="file" name="csv_file" accept=".csv">
    </label>
    <button type="submit" name="upload_csv">Upload & Hiển thị</button>
</form>

<?php if ($csvError): ?>
    <p class="error"><?= htmlspecialchars($csvError) ?></p>
<?php endif; ?>

<?php if (!empty($csvHeader)): ?>
    <table>
        <thead>
        <tr>
            <?php foreach ($csvHeader as $col): ?>
                <th><?= htmlspecialchars($col) ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($csvRows as $row): ?>
            <tr>
                <?php foreach ($row as $cell): ?>
                    <td><?= htmlspecialchars($cell) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif (isset($_POST['upload_csv']) && !$csvError): ?>
    <p>File CSV không có dữ liệu.</p>
<?php endif; ?>

</body>
</html>
