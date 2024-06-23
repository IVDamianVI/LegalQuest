<?php

declare(strict_types=1);
session_start();
$_SESSION['page'] = basename($_SERVER['PHP_SELF']);

require ('access.php');
$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
if (isset($GETIDUser)) {
    $userID = $GETIDUser;
    $username = '';
    $banner = '_default_banner.png';
    $avatarFileName = '_default_avatar.svg';
} else if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
    $username = $_SESSION['user'];
} else {
    $username = 'Gość';
    $banner = '_default_banner.png';
    $avatarFileName = '_default_avatar.svg';
    $created = '<a href="/ick/rejestracja" style="color: var(--primary-color);">Zarejestruj się</a>';
}

if (isset($_SESSION['user_id'])) {
    if ($userID != $_SESSION['user_id']) {
        $showLinkToEdit = 'display: none;';
        $isUserAccount = false;
    } else {
        $showLinkToEdit = '';
        $isUserAccount = true;
    }
} else {
    $showLinkToEdit = 'display: none;';
    $isUserAccount = false;
}
if (isset($GETIDUser)) {
    $result = mysqli_query($dbConn, "SELECT * FROM users WHERE id = $GETIDUser");
    while ($row = mysqli_fetch_assoc($result)) {
        $username = $row['username'];
        $created = $row['created'];
        $avatarFileName = $row['avatar'];
        $banner = $row['banner'];

        $created = str_replace("-", ".", $created);

        list($data, $czas) = explode(" ", $created);

        list($rok, $miesiac, $dzien) = explode(".", $data);
        $data_polish = $dzien . "." . $miesiac . "." . $rok;

        $created = $data_polish . " " . $czas;
    }

    if (mysqli_num_rows($result) == 0) {
        header('Location: /ick/');
        exit();
    }
}


$testHistory = [];
$testCounter = 0;
$correctAnswers = 0;
$wrongAnswers = 0;
$avgScore = 0;
$lastTest = '-';
if (isset($GETIDUser)) {
    $testHistoryStmt = mysqli_prepare($dbConn, "
        SELECT ut.test_id, ut.start_time, c.name AS category_name
        FROM user_tests ut
        JOIN tests t ON ut.test_id = t.id
        JOIN category c ON t.category_id = c.id
        WHERE ut.user_id = ? AND ut.score IS NOT NULL AND ut.score >= 0 AND ut.score <= 100 AND ut.end_time IS NOT NULL AND ut.start_time IS NOT NULL AND ut.end_time > ut.start_time
        ORDER BY ut.start_time DESC
    ");
    mysqli_stmt_bind_param($testHistoryStmt, 'i', $GETIDUser);
    mysqli_stmt_execute($testHistoryStmt);
    $testHistoryResult = mysqli_stmt_get_result($testHistoryStmt);
    while ($row = mysqli_fetch_assoc($testHistoryResult)) {
        $testHistory[] = $row;
        $testCounter++;
    }
    mysqli_stmt_close($testHistoryStmt);

    $correctAnswersStmt = mysqli_prepare(
        $dbConn,
        "
        SELECT COUNT(*) AS correct_answers
        FROM user_answers
        WHERE user_id = ? AND answer = 'a';"
    );
    mysqli_stmt_bind_param($correctAnswersStmt, 'i', $GETIDUser);
    mysqli_stmt_execute($correctAnswersStmt);
    $correctAnswersResult = mysqli_stmt_get_result($correctAnswersStmt);
    while ($row = mysqli_fetch_assoc($correctAnswersResult)) {
        $correctAnswers = $row['correct_answers'];
    }
    mysqli_stmt_close($correctAnswersStmt);

    $wrongAnswersStmt = mysqli_prepare(
        $dbConn,
        "
        SELECT COUNT(*) AS wrong_answers
        FROM user_answers
        WHERE user_id = ? AND answer != 'a';"
    );
    mysqli_stmt_bind_param($wrongAnswersStmt, 'i', $GETIDUser);
    mysqli_stmt_execute($wrongAnswersStmt);
    $wrongAnswersResult = mysqli_stmt_get_result($wrongAnswersStmt);
    while ($row = mysqli_fetch_assoc($wrongAnswersResult)) {
        $wrongAnswers = $row['wrong_answers'];
    }
    mysqli_stmt_close($wrongAnswersStmt);

    $avgScoreStmt = mysqli_prepare(
        $dbConn,
        "
        SELECT AVG(score) AS avg_score
        FROM user_tests
        WHERE user_id = ? AND score IS NOT NULL AND score >= 0 AND score <= 100 AND end_time IS NOT NULL AND start_time IS NOT NULL AND end_time > start_time;"
    );
    mysqli_stmt_bind_param($avgScoreStmt, 'i', $GETIDUser);
    mysqli_stmt_execute($avgScoreStmt);
    $avgScoreResult = mysqli_stmt_get_result($avgScoreStmt);
    while ($row = mysqli_fetch_assoc($avgScoreResult)) {
        $avgScore = $row['avg_score'];
    }
    mysqli_stmt_close($avgScoreStmt);

    $avgScore = round((float) $avgScore, 2);

    $lastTestStmt = mysqli_prepare(
        $dbConn,
        "
        SELECT ut.test_id, ut.start_time, c.name AS category_name
        FROM user_tests ut
        JOIN tests t ON ut.test_id = t.id
        JOIN category c ON t.category_id = c.id
        WHERE ut.user_id = ? AND ut.score IS NOT NULL AND ut.score >= 0 AND ut.score <= 100 AND ut.end_time IS NOT NULL AND ut.start_time IS NOT NULL AND ut.end_time > ut.start_time
        ORDER BY ut.start_time DESC
        LIMIT 1;"
    );
    mysqli_stmt_bind_param($lastTestStmt, 'i', $GETIDUser);
    mysqli_stmt_execute($lastTestStmt);
    $lastTestResult = mysqli_stmt_get_result($lastTestStmt);
    while ($row = mysqli_fetch_assoc($lastTestResult)) {
        $lastTest = date('d.m.Y', strtotime($row['start_time']));
    }
    mysqli_stmt_close($lastTestStmt);
}

$isFooterFixedBottom = true;
?>
<!DOCTYPE html>
<html lang="pl" data-bs-theme="dark">

<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Meta Info -->
    <meta name="author" content="Damian Grubecki, Maciej Ludwiczak">
    <meta name="description"
        content="Aplikacja webowa do nauki prawa poprzez rozwiązywanie testów i quizów, stworzona przez Damiana Grubeckiego i Macieja Ludwiczaka. Umożliwia interaktywną naukę i powtórkę materiału przed egzaminami.">
    <meta name="keywords"
        content="<?php echo $appName; ?>, nauka prawa, testy prawnicze, quizy prawnicze, interaktywna nauka, egzaminy prawnicze, aplikacja webowa">
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo $appName; ?> - Aplikacja do nauki prawa">
    <meta property="og:description"
        content="Aplikacja webowa do nauki prawa poprzez rozwiązywanie testów i quizów. Ułatwia przyswajanie wiedzy prawnej w interaktywny sposób.">
    <meta property="og:image" content="https://ivdamianvi.smallhost.pl/ick/media/logo/logo-og.png">
    <meta property="og:url" content="https://ivdamianvi.smallhost.pl/ick/">
    <meta property="og:type" content="website">
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $appName; ?> - Aplikacja do nauki prawa">
    <meta name="twitter:description"
        content="Aplikacja webowa do nauki prawa poprzez rozwiązywanie testów i quizów. Ułatwia przyswajanie wiedzy prawnej w interaktywny sposób.">
    <meta name="twitter:image" content="https://ivdamianvi.smallhost.pl/ick/media/logo/logo-og.png">
    <!-- Icon -->
    <link rel="icon" href="<?php echo $faviconSRC; ?>">
    <link rel="stylesheet" href="/ick/css/colors.css">
    <link rel="stylesheet" href="/ick/css/style-page-main.css">
    <link rel="stylesheet" href="/ick/css/style-page-profile.css">
    <link rel="stylesheet" href="/ick/css/lightModeColors.css">
    <link rel="stylesheet" href="/ick/css/darkModeColors.css">
    <!-- GeoIP2 -->
    <script src="//geoip-js.com/js/apis/geoip2/v2.1/geoip2.js"></script>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript" language="javascript"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js" type="text/javascript"
        language="javascript"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js" type="text/javascript"
        language="javascript"></script>

    <!-- Title -->
    <title><?php echo "Profil $username | $appName"; ?></title>

    <style>
        .topic {
            margin-bottom: 20px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .topic-title {
            margin: 0;
            color: var(--primary-color);
            font-size: 1.2em;
        }

        .topic-info {
            margin: 5px 0 0;
            font-size: 0.9em;
            color: #aaa;
        }

        .topic-username {
            color: #fff;
        }

        .topic-date {
            color: #bbb;
        }

        .topic a {
            text-decoration: none;
            color: inherit;
        }

        .topic a:hover {
            text-decoration: underline;
        }

        a.link-to-test {
            text-decoration: none;
            color: #fff;
        }

        a.link-to-test:hover {
            color: var(--primary-color);
            cursor: pointer;
        }

        .card-header {
            background-color: var(--primary-color);
            color: #000;
            font-weight: bold;
        }

        .card-body {
            background-color: #ffffff10;
            color: #fff;
        }

        .card-title {
            font-size: 1.6em;
        }

        .col-1 {
            width: 10%;
        }

        .correct-answer {
            color: #28a745;
        }

        .wrong-answer {
            color: #dc3545;
        }

        .list-group-item {
            background-color: #ffffff10;
            border: 1px solid #ffffff20;
            color: #fff;
            margin-bottom: 5px;
        }
    </style>
</head>

<body onload="autoSubmitForm()">
    <?php include "header.php"; ?>
    <?php include "loading.php"; ?>
    <main class="container">
        <div class="container banner"
            style='background-color: #000000; background-image: url("/ick/media/banner/<?php echo $banner; ?>"); background-size: cover; background-repeat: no-repeat; background-position: center center; box-shadow: inset 0px -140px 140px -70px #000000;'>
            <a href="/ick/zmien-baner" style="<?php echo $showLinkToEdit; ?>">
                <span id="changeBannerIcon"><i class="bi bi-image"></i></span>
            </a>
            <div class="container banner-inside" style="padding: 5px; padding-top: 6em; padding-bottom: 10px;">
                <div class="row align-items-end">
                    <div class="col avatarImage" style="max-width: 12.5em !important;">
                        <?php if ($isUserAccount): ?>
                            <a id="changeAvatar" href="/ick/zmien-avatar">
                                <span id="changeAvatarIcon"><i class="bi bi-person-bounding-box"></i></span>
                            <?php endif; ?>
                            <img id="changeAvatarImg" src="/ick/media/avatar/<?php echo $avatarFileName; ?>"
                                alt="Avatar"
                                style="background-color: #161616; width: 12em; height: 12em; margin: 0; padding: 0;" />
                            <?php if ($isUserAccount): ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="col" style="text-align: left;">
                        <span style="color: #ffffff; font-weight:bold; font-size: 2em;">
                            <?php echo $username; ?>
                        </span><br>
                        <span style="color: #ffffff; font-size: 1em;">Utworzono:
                            <?php
                            $created = substr($created, 0, 10);
                            echo $created . " r." ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <div class="card text-white card-testy mb-3">
                    <div class="card-header"><i class="bi bi-clipboard2-fill"></i> Ukończone testy</div>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <?php echo $testCounter; ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white card-odpowiedzi mb-3">
                    <div class="card-header"><i class="bi bi-patch-question-fill"></i> Odpowiedzi</div>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <?php echo '<span class="correct-answer">' . $correctAnswers . '</span> | <span class="wrong-answer">' . $wrongAnswers . '</span>'; ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white card-srednia mb-3">
                    <div class="card-header"><i class="bi bi-award-fill"></i> Średni wynik</div>
                    <div class="card-body">
                        <h5 class="card-title text-center"><?php echo $avgScore; ?>%</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white card-ostatni mb-3">
                    <div class="card-header"><i class="bi bi-clock-history"></i> Ostatni test</div>
                    <div class="card-body">
                        <h5 class="card-title text-center"><?php echo $lastTest; ?></h5>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="topic-title"><i class="bi bi-trophy-fill"></i> Osiągnięcia</h2>
        <p class="topic-info">Tutaj znajdziesz swoje osiągnięcia.</p>
        <br />
        <?php if ($isUserAccount): ?>
            <h2 class="topic-title"><i class="bi bi-clock-history"></i> Historia testów</h2>
            <p class="topic-info">Tutaj znajdziesz historię swoich testów.</p>
            <ul class="list-group">
                <?php foreach ($testHistory as $test): ?>
                    <li class="list-group list-group-item">
                        <a class="link-to-test" href="/ick/wynik-testu/<?php echo $test['test_id']; ?>">
                            <i class="bi bi-clipboard2-fill"></i> Test z <?php echo htmlspecialchars($test['category_name']); ?>
                            -
                            <?php echo date('d.m.Y H:i', strtotime($test['start_time'])); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>
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