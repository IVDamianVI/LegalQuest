<?php
declare(strict_types=1);
session_start();
require ('../access.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: /ick/');
    exit();
}

$user_id = $_SESSION['user_id'];
$question_index = (int) $_POST['question_index'];
$answer = $_POST['answer'] ?? 'x';
$test_id = $_SESSION['test_id'];

$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
if (!$dbConn) {
    die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
}

$questions = $_SESSION['questions'];
$current_question = $questions[$question_index];

$stmt = mysqli_prepare($dbConn, "INSERT INTO user_answers (user_id, question_id, answer, test_id) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE answer = VALUES(answer)");
mysqli_stmt_bind_param($stmt, 'iisi', $user_id, $current_question, $answer, $test_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
mysqli_close($dbConn);

header('Location: /ick/test.php?question=' . ($question_index + 1));
exit();
?>