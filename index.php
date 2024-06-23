<?php

declare(strict_types=1);
session_start();
require ('access.php');
$_SESSION['page'] = basename($_SERVER['PHP_SELF']);
$isFooterFixedBottom = true;
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
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/style-page-main.css">
    <link rel="stylesheet" href="css/style-page-index.css">
    <link rel="stylesheet" href="css/lightModeColors.css">
    <link rel="stylesheet" href="css/darkModeColors.css">
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
    <title><?php echo $appName; ?> | Strona Główna</title>
</head>


<body onload="autoSubmitForm()">
    <?php include "header.php"; ?>
    <?php include "loading.php"; ?>
    <main class="container">
        <div class="text-center">
            <h1><?php echo $appName; ?></h1>
        </div>
        <div class="text-center">
            <p><?php echo $appName; ?> to aplikacja webowa do nauki prawa poprzez rozwiązywanie testów i quizów.
                Stworzona, aby ułatwić przyswajanie wiedzy prawnej w interaktywny sposób oraz zwiększyć zaangażowanie w
                proces nauki. Aplikacja jest idealna dla studentów prawa oraz osób przygotowujących się do egzaminów
                prawniczych.</p>
        </div>
        <div id="icon-menu">
            <?php
            $dbConnection = new mysqli($dbHost, $dbUsername, $dbPassword, $dbDatabase);
            $dbConnection->set_charset('utf8');
            $sql = "SELECT * FROM category";
            $result = $dbConnection->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $disabled = '';
                    $categoryName = $row['name'];
                    if ($row['id'] == 2) {
                        $disabled = '';
                    }
                    $categoryIcon = '<img src="media/icon/' . $row['icon'] . '" alt="' . $categoryName . '">';
                    echo '<a href="/ick/kategoria/' . $row['url_name'] . '" ' . $disabled . '>';
                    echo '<div class="category cat-1">';
                    echo '<div class="category-icon">';
                    echo $categoryIcon;
                    echo '</div>';
                    echo '<div class="category-info">';
                    echo '<p class="category-title">' . $row['name'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                }
            }
            ?>
        </div>
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