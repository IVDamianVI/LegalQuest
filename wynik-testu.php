<?php

declare(strict_types=1);
session_start();
require ('access.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: /ick/');
    exit();
}

$user_id = $_SESSION['user_id'];
$test_id = isset($GETTestID) ? (int) $GETTestID : $_SESSION['test_id'];

$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
if (!$dbConn) {
    die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
}

$stmt = mysqli_prepare($dbConn, "SELECT q.question, q.answer_a, q.answer_b, q.answer_c, ua.answer
    FROM questions q
    JOIN user_answers ua ON q.id = ua.question_id
    WHERE ua.user_id = ? AND ua.test_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $user_id, $test_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$questions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $questions[] = $row;
}

mysqli_stmt_close($stmt);

$total_questions = count($questions);
$correct_answers = 0;

foreach ($questions as $index => $question) {
    $user_answer = $question['answer'];
    if ($user_answer === 'a') {
        $correct_answers++;
    }
}

$score_percentage = round(($correct_answers / $total_questions) * 100, 2);

$end_date = date('Y-m-d H:i:s');
$stmt = mysqli_prepare($dbConn, "UPDATE user_tests SET score = ?, end_time = ? WHERE user_id = ? AND test_id = ?");
mysqli_stmt_bind_param($stmt, 'dsii', $score_percentage, $end_date, $user_id, $test_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($dbConn, "UPDATE tests SET end_date = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'si', $end_date, $test_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

mysqli_close($dbConn);

if ($total_questions >= 2) {
    $isFooterFixedBottom = true;
}

?>
<!DOCTYPE html>
<html lang="pl" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Damian Grubecki, Maciej Ludwiczak">
    <meta name="description"
        content="Aplikacja webowa do nauki prawa poprzez rozwiązywanie testów i quizów, stworzona przez Damiana Grubeckiego i Macieja Ludwiczaka. Umożliwia interaktywną naukę i powtórkę materiału przed egzaminami.">
    <meta name="keywords"
        content="<?php echo $appName; ?>, nauka prawa, testy prawnicze, quizy prawnicze, interaktywna nauka, egzaminy prawnicze, aplikacja webowa">
    <meta property="og:title" content="<?php echo $appName; ?> - Aplikacja do nauki prawa">
    <meta property="og:description"
        content="Aplikacja webowa do nauki prawa poprzez rozwiązywanie testów i quizów. Ułatwia przyswajanie wiedzy prawnej w interaktywny sposób.">
    <meta property="og:image" content="https://ivdamianvi.smallhost.pl/ick/media/logo/logo-og.png">
    <meta property="og:url" content="https://ivdamianvi.smallhost.pl/ick/">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $appName; ?> - Aplikacja do nauki prawa">
    <meta name="twitter:description"
        content="Aplikacja webowa do nauki prawa poprzez rozwiązywanie testów i quizów. Ułatwia przyswajanie wiedzy prawnej w interaktywny sposób.">
    <meta name="twitter:image" content="https://ivdamianvi.smallhost.pl/ick/media/logo/logo-og.png">
    <link rel="icon" href="<?php echo $faviconSRC; ?>">
    <link rel="stylesheet" href="/ick/css/colors.css">
    <link rel="stylesheet" href="/ick/css/style-page-main.css">
    <link rel="stylesheet" href="/ick/css/style-page-wynik-testu.css">
    <link rel="stylesheet" href="/ick/css/lightModeColors.css">
    <link rel="stylesheet" href="/ick/css/darkModeColors.css">
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
    <style>
        .correct-answer {
            color: #48fc72;
            font-weight: bold;
        }

        .incorrect-answer {
            color: #ff6b79;
        }

        .result-circle-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        .result-circle {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            color: white;
            background: conic-gradient(var(--primary-color) calc(var(--percentage) * 1%), var(--background-page-color) 0);
        }

        .result-circle.red {
            --primary-color: #ff0000;
        }

        .result-circle.orange {
            --primary-color: #ffa500;
        }

        .result-circle.yellow {
            --primary-color: #ffff00;
        }

        .result-circle.green {
            --primary-color: #00ff00;
        }

        .result-circle .inner-circle {
            position: absolute;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: var(--dark-grey-color);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
        }

        .result-circle span {
            z-index: 3;
            font-size: 42px;
            opacity: 0;
            transition: opacity 2s;
        }

        #question {
            font-size: 18px;
        }
    </style>
</head>

<body>
    <?php include "header.php"; ?>
    <main class="container">
        <h1>Wynik testu</h1>
        <hr />
        <div class="result-circle-container">
            <div class="result-circle" id="result-circle">
                <div class="inner-circle">
                    <span id="result-percentage"><?php echo $score_percentage; ?>%</span>
                </div>
            </div>
        </div>

        <div class="text-center">
            <p>Poprawnych odpowiedzi: <?php echo $correct_answers; ?> / <?php echo $total_questions; ?></p>
        </div>

        <h2>Twoje odpowiedzi:</h2>
        <ul class="list-group">
            <?php foreach ($questions as $index => $question): ?>
                <li class="list-group-item">
                    <span id="question"><strong>Pytanie <?php echo $index + 1; ?>:</strong>
                        <?php echo htmlspecialchars($question['question']); ?></span><br>
                    <?php if ($question['answer'] === 'a'): ?>
                        <strong class="correct-answer"><i class="bi bi-check-lg" style="color: #00ff00"></i>
                            <?php echo htmlspecialchars($question['answer_a']); ?></strong>
                    <?php else: ?>
                        <?php if ($question['answer'] === 'x'): ?>
                            <strong class="correct-answer"><i class="bi bi-check-lg" style="color: #00ff00"></i>
                                <?php echo htmlspecialchars($question['answer_a']); ?></strong><br>
                            <strong class="incorrect-answer"><i class="bi bi-x-lg" style="color: #ff0000"></i>
                                Nie udzielono odpowiedzi</strong>
                        <?php else: ?>
                            <strong class="correct-answer"><i class="bi bi-check-lg" style="color: #00ff00"></i>
                                <?php echo htmlspecialchars($question['answer_a']); ?></strong><br>
                            <strong class="incorrect-answer"><i class="bi bi-x-lg" style="color: #ff0000"></i>
                                <?php echo htmlspecialchars($question['answer_' . $question['answer']]); ?></strong>
                        <?php endif; ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

    </main>
    <?php require_once 'footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scorePercentage = <?php echo $score_percentage; ?>;
            const resultCircle = document.getElementById('result-circle');
            const resultPercentage = document.getElementById('result-percentage');

            if (scorePercentage >= 90) {
                resultCircle.classList.add('green');
            } else if (scorePercentage >= 70) {
                resultCircle.classList.add('yellow');
            } else if (scorePercentage >= 50) {
                resultCircle.classList.add('orange');
            } else {
                resultCircle.classList.add('red');
            }

            resultCircle.style.setProperty('--percentage', scorePercentage);

            setTimeout(() => {
                resultPercentage.style.opacity = 1;
            }, 100);
        });
    </script>

</body>

</html>