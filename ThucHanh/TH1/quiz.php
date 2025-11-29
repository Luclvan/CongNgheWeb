<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/* ========= ĐỌC FILE QUIZ ========= */
$filename = "Quiz.txt";
$content = file_get_contents($filename);
$rawQuestions = preg_split("/\n\s*\n/", trim($content));

$questions = [];

foreach ($rawQuestions as $block) {
    preg_match('/^(.*)\nA\.(.*)\nB\.(.*)\nC\.(.*)\nD\.(.*)\nANSWER:\s*(.*)$/s', trim($block), $m);

    if (count($m) > 0) {
        $answers = array_map('trim', explode(',', strtoupper(trim($m[6]))));

        $questions[] = [
            'question' => trim($m[1]),
            'A' => trim($m[2]),
            'B' => trim($m[3]),
            'C' => trim($m[4]),
            'D' => trim($m[5]),
            'correct' => $answers
        ];
    }
}

/* ========= CHẤM ĐIỂM ========= */
$score = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;

    foreach ($questions as $i => $q) {
        // Lấy dữ liệu người dùng chọn
        $userRaw = $_POST['q'.$i] ?? [];

        // Nếu chỉ nhận được 1 giá trị (string) thì ép thành mảng
        if (!is_array($userRaw)) {
            $userAns = [$userRaw];
        } else {
            $userAns = $userRaw;
        }

        // Đáp án đúng luôn là mảng
        $right = $q['correct'];

        sort($userAns);
        sort($right);

        $isCorrect = ($userAns === $right);
        if ($isCorrect) {
            $score++;
        }

        $resultDetail[$i] = [
            'user' => $userAns,
            'correct' => $right,
            'isCorrect' => $isCorrect
        ];
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Bài thi trắc nghiệm</title>
<style>
body{font-family:Arial;max-width:900px;margin:auto;padding:20px}
.question{margin-bottom:20px;padding:15px;border:1px solid #ccc}
.correct{background:#e6ffe6;border:2px solid green}
.wrong{background:#ffe6e6;border:2px solid red}
.answer{color:blue;font-weight:bold;margin-top:8px}
.result{font-size:20px;font-weight:bold;color:green}
input{margin-right:8px}
</style>
</head>
<body>

<h1>BÀI THI TRẮC NGHIỆM ANDROID</h1>

<form method="post">

<?php foreach ($questions as $i => $q): 
$class = '';
if (isset($resultDetail[$i])) {
    $class = $resultDetail[$i]['isCorrect'] ? 'correct' : 'wrong';
}
?>
<div class="question <?= $class ?>">
    <p><b>Câu <?= $i+1 ?>:</b> <?= $q['question'] ?></p>

    <?php foreach (['A','B','C','D'] as $opt): 
        $checked = (isset($resultDetail[$i]) && in_array($opt, $resultDetail[$i]['user'])) ? 'checked' : '';
    ?>
        <label>
            <input type="checkbox" name="q<?= $i ?>[]" value="<?= $opt ?>" <?= $checked ?>>
            <?= $opt ?>. <?= $q[$opt] ?>
        </label><br>
    <?php endforeach; ?>

    <?php if (isset($resultDetail[$i])): ?>
        <div class="answer">
            ✅ Đáp án đúng: <?= implode(', ', $resultDetail[$i]['correct']) ?>
        </div>
        <div>
            👉 Bạn chọn: 
            <?= empty($resultDetail[$i]['user']) ? 'Không chọn' : implode(', ', $resultDetail[$i]['user']) ?>
        </div>
    <?php endif; ?>
</div>
<?php endforeach; ?>

<button type="submit">Nộp bài</button>

</form>

<?php if ($score !== null): ?>
    <p class="result">
        ✅ Kết quả: <?= $score ?> / <?= count($questions) ?> câu đúng
    </p>
<?php endif; ?>

</body>
</html>
