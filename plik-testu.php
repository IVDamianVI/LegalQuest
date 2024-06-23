<?php

declare(strict_types=1);
session_start();
require ('access.php');

$user_id = $_SESSION['user_id'];

$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
if (!$dbConn) {
    die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
}

$stmt = mysqli_prepare($dbConn, "SELECT q.question, q.answer_a, q.answer_b, q.answer_c, q.correct_answer_hash, ua.answer
    FROM questions q
    JOIN user_answers ua ON q.id = ua.question_id
    WHERE ua.user_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$questions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $questions[] = $row;
}

mysqli_stmt_close($stmt);
mysqli_close($dbConn);

$total_questions = count($questions);
$correct_answers = 0;

foreach ($questions as $index => $question) {
    $correct_answer = $question['correct_answer_hash'];
    $user_answer = $question['answer'];
    $shuffled_answer = $user_answer;

    if (hash('sha256', $shuffled_answer) === $correct_answer) {
        $correct_answers++;
    }
}

?>
<!DOCTYPE html>
<html lang="pl" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Damian Grubecki, Maciej Ludwiczak">
    <meta name="description" content="">
    <meta name="keywords" content="quiz, test, results">
    <link rel="stylesheet" href="/ick/css/colors.css">
    <link rel="stylesheet" href="/ick/css/style-page-main.css">
    <link rel="stylesheet" href="/ick/css/style-page-results.css">
    <link rel="stylesheet" href="/ick/css/lightModeColors.css">
    <link rel="stylesheet" href="/ick/css/darkModeColors.css">
    <link rel="icon" href="/ick/<?php echo $faviconSRC; ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript" language="javascript"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js" type="text/javascript"
        language="javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <title>Wyniki testu | <?php echo $appName; ?></title>
</head>

<body>
    <?php include "header.php"; ?>
    <?php include "loading.php"; ?>
    <main class="container">
        <h1>Wyniki testu</h1>
        <p>Poprawnych odpowiedzi: <?php echo $correct_answers; ?> z <?php echo $total_questions; ?></p>
        <p>Twój wynik: <?php echo round(($correct_answers / $total_questions) * 100, 2); ?>%</p>

        <h2>Twoje odpowiedzi:</h2>
        <ul class="list-group">
            <?php foreach ($questions as $index => $question): ?>
                <li class="list-group-item">
                    <strong>Pytanie <?php echo $index + 1; ?>:</strong>
                    <?php echo htmlspecialchars($question['question']); ?><br>
                    <strong>Twoja odpowiedź:</strong> <?php echo strtoupper($question['answer']); ?><br>
                    <strong>Poprawna odpowiedź:</strong>
                    <?php echo strtoupper(array_search($question['correct_answer_hash'], array_map(function ($a) {
                        return hash('sha256', $a);
                    }, ['a' => $question['answer_a'], 'b' => $question['answer_b'], 'c' => $question['answer_c']]))) ?: 'Nieznana'; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="/ick/" class="btn btn-primary mt-3">Wróć do strony głównej</a>
    </main>
    <?php require_once 'footer.php'; ?>
</body>

</html>