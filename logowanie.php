<?php
declare(strict_types=1);
session_start();
require ('access.php');
$current_page = basename($_SERVER['PHP_SELF']);
if (isset($_SESSION['loggedin'])) {
    header('Location: /');
    exit();
}
$_SESSION['page'] = basename($_SERVER['PHP_SELF']);
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
    <link rel="stylesheet" href="css/style-page-form.css">
    <script src="//geoip-js.com/js/apis/geoip2/v2.1/geoip2.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript" language="javascript"></script>
    <title>Zaloguj Się | <?php echo $appName; ?></title>
</head>

<body onload="autoSubmitForm()" class="text-center background-first">
    <div class="form-register background-second">
        <div class="logo">
            <img height="60px" id="logo" src="<?php echo $logoSRC; ?>" alt="Logo" title="Logo">
        </div>
        <h1 id="title">Zaloguj się</h1>
        <span id="subtitle">Uzyskaj dostęp do swojego konta</span>
        <form class="form-signin form-register" method="post" action="controller/logowanie-controller.php">
            <?php
            session_start();
            if (isset($_SESSION['error_message'])) {
                echo '<div class="alert alert-danger" role="alert">';
                echo '<i class="bi bi-exclamation-triangle-fill"></i> Wystąpił problem<hr>';
                echo $_SESSION['error_message'];
                echo '</div>';
                unset($_SESSION['error_message']);
            } else if (isset($_SESSION['success_message'])) {
                echo '<div class="alert alert-success" role="alert">';
                echo '<i class="bi bi-check-circle-fill"></i> Sukces<hr>';
                echo $_SESSION['success_message'];
                echo '</div>';
                unset($_SESSION['success_message']);
            }
            ?>
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" minlength="4" placeholder="Login"
                    name="user">
                <label for="floatingInput">Nazwa użytkownika (4+ znaki)</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="pass" minlength="4" placeholder="Hasło" name="pass">
                <label for="pass">Hasło (min. 4 znaki)</label>
            </div>
            <button class="btn btn-lg btn-primary" type="submit" disabled>Zaloguj</button>
        </form>
        <div class="mb-3">
            <span class="color-text-icon">Nie masz jeszcze konta? <a id="link" href="/ick/rejestracja">Zarejestruj
                    się</a>
                tutaj</span>
            <br>
            <a id="link" href="/ick/" style="font-weight: unset;">Wróć na stronę główną</a>
        </div>
    </div>
    <script src="script/buttonLogin.js" type="text/javascript"></script>
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