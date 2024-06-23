<?php

declare(strict_types=1);
session_start();
require ('../access.php');

$category_id = $_POST['category_id'];
$number_of_questions = (int) $_POST['number_of_questions'];
$time_option = $_POST['time_option'];
$max_time_per_question = isset($_POST['max_time_per_question']) ? (int) $_POST['max_time_per_question'] : null;
$max_time_test = isset($_POST['max_time_test']) ? (int) $_POST['max_time_test'] : null;

$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
if (!$dbConn) {
    die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
}

$time_limit = ($time_option === 'no_time_limit') ? 0 : 1;
$stmt = mysqli_prepare($dbConn, "INSERT INTO tests (category_id, time_limit, question_count) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'iii', $category_id, $time_limit, $number_of_questions);
mysqli_stmt_execute($stmt);
$test_id = mysqli_insert_id($dbConn);
mysqli_stmt_close($stmt);

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = mysqli_prepare($dbConn, "INSERT INTO user_tests (user_id, test_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $test_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

$stmt = mysqli_prepare($dbConn, "SELECT id FROM questions WHERE category_id = ? ORDER BY RAND() LIMIT ?");
mysqli_stmt_bind_param($stmt, 'ii', $category_id, $number_of_questions);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$questions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $questions[] = $row['id'];
}

mysqli_stmt_close($stmt);

foreach ($questions as $question_id) {
    $stmt = mysqli_prepare($dbConn, "INSERT INTO user_answers (user_id, question_id, test_id) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'iii', $user_id, $question_id, $test_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

mysqli_close($dbConn);

$_SESSION['test_id'] = $test_id;
$_SESSION['questions'] = $questions;
$_SESSION['time_option'] = $time_option;
$_SESSION['max_time_per_question'] = $max_time_per_question;
$_SESSION['max_time_test'] = $max_time_test;

header('Location: /ick/test.php');
exit();


?>