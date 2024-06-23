<?php

declare(strict_types=1);
session_start();
require ('access.php');

if (!isset($_SESSION['questions'])) {
    header('Location: /ick/');
    exit();
}

$test_id = $_SESSION['test_id'];
$questions = $_SESSION['questions'];
$time_option = $_SESSION['time_option'];
$max_time_per_question = $_SESSION['max_time_per_question'];
$max_time_test = $_SESSION['max_time_test'];

$current_question_index = isset($_GET['question']) ? (int) $_GET['question'] : 0;

if ($current_question_index >= count($questions)) {
    header('Location: /ick/wynik-testu');
    exit();
}

$current_question_id = $questions[$current_question_index];
$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
$stmt = mysqli_prepare($dbConn, "SELECT * FROM questions WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $current_question_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$current_question = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($dbConn);

$answers = [
    'a' => $current_question['answer_a'],
    'b' => $current_question['answer_b'],
    'c' => $current_question['answer_c']
];
$shuffled_keys = array_keys($answers);
shuffle($shuffled_keys);

$_SESSION['shuffled_keys'][$current_question_index] = $shuffled_keys;

?>
<!DOCTYPE html>
<html lang="pl" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Damian Grubecki, Maciej Ludwiczak">
    <meta name="description" content="">
    <meta name="keywords" content="quiz, test">
    <link rel="stylesheet" href="/ick/css/colors.css">
    <link rel="stylesheet" href="/ick/css/style-page-main.css">
    <link rel="stylesheet" href="/ick/css/style-page-test.css">
    <link rel="stylesheet" href="/ick/css/lightModeColors.css">
    <link rel="stylesheet" href="/ick/css/darkModeColors.css">
    <link rel="icon" href="<?php echo $faviconSRC; ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript" language="javascript"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js" type="text/javascript"
        language="javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js" type="text/javascript"
        language="javascript"></script>
    <title>Test | <?php echo $appName; ?></title>
    <style>
        h1 {
            color: var(--primary-color) !important;
        }
    </style>
</head>

<body>
    <?php include "header.php"; ?>
    <main class="container" style="position: relative;">
        <div id="timer"></div>
        <h1>Pytanie <?php echo $current_question_index + 1; ?> z <?php echo count($questions); ?></h1>
        <hr />
        <h3><?php echo htmlspecialchars($current_question['question']); ?></h3>
        <form id="questionForm" action="controller/save-answer.php" method="POST">
            <?php foreach ($shuffled_keys as $key): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" id="answer_<?php echo $key; ?>"
                        value="<?php echo $key; ?>" required>
                    <label class="form-check-label"
                        for="answer_<?php echo $key; ?>"><?php echo htmlspecialchars($answers[$key]); ?></label>
                </div>
            <?php endforeach; ?>
            <input type="hidden" name="question_index" value="<?php echo $current_question_index; ?>">
            <input type="hidden" name="timeout" value="0" id="timeout">
            <div class="text-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-caret-right-fill"></i> Następne
                    pytanie</button>
            </div>
        </form>
    </main>
    <?php require_once 'footer.php'; ?>
    <script>
        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
        }

        function submitFormWithTimeout() {
            document.getElementById('timeout').value = '1';
            document.getElementById('questionForm').submit();
        }

        <?php if ($time_option === 'time_limit_per_question' && $max_time_per_question): ?>
            let timeLeft = <?php echo $max_time_per_question * 60; ?>;
            const timer = setInterval(function () {
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    submitFormWithTimeout();
                }
                document.getElementById('timer').textContent = formatTime(timeLeft);
                timeLeft -= 1;
            }, 1000);
        <?php elseif ($time_option === 'time_limit_per_test' && $max_time_test): ?>
            let totalTimeLeft = <?php echo $max_time_test * 60; ?>;
            const totalTimer = setInterval(function () {
                if (totalTimeLeft <= 0) {
                    clearInterval(totalTimer);
                    submitFormWithTimeout();
                }
                document.getElementById('timer').textContent = formatTime(totalTimeLeft);
                totalTimeLeft -= 1;
            }, 1000);
        <?php endif; ?>
    </script>
</body>

</html>