<?php

declare(strict_types=1);
session_start();
require ('access.php');
$_SESSION['page'] = basename($_SERVER['PHP_SELF']);
$isFooterFixedBottom = true;

$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
if ($dbConn) {
    mysqli_query($dbConn, "SET NAMES 'utf8'");
    $stmt = mysqli_prepare($dbConn, "SELECT * FROM category WHERE url_name=?");
    mysqli_stmt_bind_param($stmt, 's', $GETkategoria);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $category = mysqli_fetch_assoc($result);
    if ($category) {
        $categoryName = $category['name'];
        $categoryIcon = $category['icon'];
    } else {
        header('Location: /ick/');
        exit();
    }
} else {
    header('Location: /ick/');
    exit();
}

$questionCounter = 0;
$stmt = mysqli_prepare($dbConn, "SELECT COUNT(*) FROM questions WHERE category_id=?");
mysqli_stmt_bind_param($stmt, 'i', $category['id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$questionCounter = mysqli_fetch_assoc($result)['COUNT(*)'];
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
    <link rel="stylesheet" href="/ick/css/style-page-kategoria.css">
    <link rel="stylesheet" href="/ick/css/lightModeColors.css">
    <link rel="stylesheet" href="/ick/css/darkModeColors.css">
    <script src="//geoip-js.com/js/apis/geoip2/v2.1/geoip2.js"></script>
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
    <title><?php echo "$categoryName | $appName"; ?></title>
    <style>
        h1 {
            color: var(--primary-color) !important;
        }

        h2 img {
            height: 2em;
        }

        .question-options {
            display: flex;
            gap: 10px;
        }

        .question-option {
            width: 50px;
            height: 50px;
            font-size: 1.3em;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid var(--primary-color);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
        }

        .question-option.selected {
            background-color: var(--primary-color);
            color: #000;
            font-weight: bold;
        }

        .question-option:hover {
            background-color: var(--primary-color);
            color: #000;
            font-weight: bold;
            scale: 1.1;
        }

        .link-login {
            color: var(--primary-color);
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .link-login:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body onload="autoSubmitForm()">
    <?php include "header.php"; ?>
    <?php include "loading.php"; ?>
    <main class="container">
        <h1>Przygotowanie testu</h1>
        <hr />
        <?php if (!isset($_SESSION['loggedin'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Uwaga!</strong> <a class="link-login" href="/ick/logowanie">Zaloguj się</a>, aby rozpocząć test.
            </div>
        <?php endif; ?>
        <h2><img src="/ick/media/icon/<?php echo $categoryIcon; ?>"> <?php echo $categoryName; ?></h2>
        <h3><i class="bi bi-gear"></i> Wybierz opcje testu i rozpocznij naukę.</h3>
        <br />
        <form action="/ick/controller/start-test-controller.php" method="POST">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" id="no_time_limit" name="time_option"
                            value="no_time_limit" checked>
                        <label class="form-check-label" for="no_time_limit">
                            Ograniczenie czasowe wyłączone
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" id="time_limit_per_question" name="time_option"
                            value="time_limit_per_question">
                        <label class="form-check-label" for="time_limit_per_question">
                            Ograniczenie czasowe włączone
                        </label>
                    </div>
                    <div class="form-check mb-3" hidden>
                        <input class="form-check-input" type="radio" id="time_limit_per_test" name="time_option"
                            value="time_limit_per_test">
                        <label class="form-check-label" for="time_limit_per_test">
                            Ograniczenie czasowe na test
                        </label>
                    </div>
                    <div class="mb-3 hidden" id="time_per_question_div">
                        <label for="max_time_per_question" class="form-label"><i class="bi bi-hourglass-split"></i>
                            Maksymalny czas na odpowiedź jednego pytania</label>
                        <select class="form-select form-select-time" id="max_time_per_question"
                            name="max_time_per_question">
                            <option value="1">1 minuta</option>
                            <option value="2">2 minuty</option>
                            <option value="3">3 minuty</option>
                            <option value="4">4 minuty</option>
                            <option value="5">5 minut</option>
                        </select>
                    </div>
                    <div class="mb-3 hidden" id="time_per_test_div">
                        <label for="max_time_test" class="form-label"><i class="bi bi-hourglass-split"></i> Maksymalny
                            czas na cały test</label>
                        <select class="form-select form-select-time" id="max_time_test" name="max_time_test">
                            <option value="5">5 minut</option>
                            <option value="10">10 minut</option>
                            <option value="15">15 minut</option>
                            <option value="20">20 minut</option>
                            <option value="30">30 minut</option>
                            <option value="60">60 minut</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-question-circle-fill"></i> Wybierz ilość pytań</label>
                        <div class="question-options">
                            <div class="question-option selected" data-value="2">2</div>
                            <div class="question-option" data-value="5">5</div>
                            <div class="question-option" data-value="10">10</div>
                            <div class="question-option" data-value="15">15</div>
                            <div class="question-option" data-value="20">20</div>
                            <div class="question-option" data-value="25">25</div>
                        </div>
                        <input type="hidden" name="number_of_questions" id="number_of_questions" value="2" required>
                    </div>
                </div>
            </div>
            <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
            <?php if (isset($_SESSION['loggedin'])): ?>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-caret-right-fill"></i> Rozpocznij
                        test</button>
                </div>
            <?php endif; ?>
        </form>
        <br />
        <hr />
        <div class="text-center">
            <p>Baza pytań: <?php echo $questionCounter; ?></p>
        </div>
    </main>
    <?php require_once 'footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const options = document.querySelectorAll('.question-option');
            const hiddenInput = document.getElementById('number_of_questions');

            options.forEach(option => {
                option.addEventListener('click', function () {
                    options.forEach(opt => opt.classList.remove('selected'));
                    option.classList.add('selected');
                    hiddenInput.value = option.getAttribute('data-value');
                });
            });

            const timeOptionRadios = document.querySelectorAll('input[name="time_option"]');
            const timePerQuestionDiv = document.getElementById('time_per_question_div');
            const timePerTestDiv = document.getElementById('time_per_test_div');

            function updateVisibility() {
                let selectedValue = document.querySelector('input[name="time_option"]:checked').value;

                if (selectedValue === 'no_time_limit') {
                    timePerQuestionDiv.classList.add('hidden');
                    timePerTestDiv.classList.add('hidden');
                    timePerQuestionDiv.classList.remove('visible');
                    timePerTestDiv.classList.remove('visible');
                } else if (selectedValue === 'time_limit_per_question') {
                    timePerQuestionDiv.classList.add('visible');
                    timePerQuestionDiv.classList.remove('hidden');
                    timePerTestDiv.classList.add('hidden');
                    timePerTestDiv.classList.remove('visible');
                } else if (selectedValue === 'time_limit_per_test') {
                    timePerTestDiv.classList.add('visible');
                    timePerTestDiv.classList.remove('hidden');
                    timePerQuestionDiv.classList.add('hidden');
                    timePerQuestionDiv.classList.remove('visible');
                }
            }

            timeOptionRadios.forEach(radio => {
                radio.addEventListener('change', updateVisibility);
            });

            updateVisibility();
        });
    </script>
    <?php require_once 'footer.php'; ?>
    <form method="POST" id="getInfo" name="getInfo">
        <input type="hidden" value="" id="display" name="display" />
        <input type="hidden" value="" id="viewport" name="viewport" />
        <input type="hidden" value="" id="colors" name="colors" />
        <input type="hidden" value="" id="cookies" name="cookies" />
        <input type="hidden" value="" id="java" name="java" />
        <input type="hidden" value="" id="page" name="page" />
        <input type="hidden" value="" id="city" name="city" />
        <input type="hidden" value="" id="coords" name="coords" />
    </form>
    <script type="text/javascript" src="script/getInfo.js"></script>
    <script>
        function autoSubmitForm() {
            var formData = new FormData(document.getElementById("getInfo"));
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "controller/get-user-info-controller.php", true);
            xhr.onload = function () {
                if (xhr.status === 200) { }
            };
            xhr.send(formData);
        }
    </script>
</body>

</html>