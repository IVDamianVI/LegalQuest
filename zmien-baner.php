<?php

declare(strict_types=1);
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['loggedin'])) {
    header('Location: logowanie.php');
    exit();
}
include ('controller/login-check-controller.php');
include ('controller/logout-auto-controller.php');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js" type="text/javascript"
        language="javascript"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript" language="javascript"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js" type="text/javascript"
        language="javascript"></script>
    <title>Zmień Baner | <?php echo $appName; ?></title>
</head>

<body class="text-center background-first">
    <div class="form-register background-second">
        <div class="logo">
            <img height="60px" id="logo" src="<?php echo $logoSRC; ?>" alt="Logo" title="Logo">
        </div>
        <h1 id="title">Zmień tło</h1>
        <span id="subtitle">Wybierz nowy baner dla swojego profilu</span>
        <form class="form-signin form-register" action="controller/zmien-baner-controller.php" method="post"
            enctype="multipart/form-data">
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
            <div class="banner-container" style="margin-top: 1em">
                <img src="media/banner/<?php echo $_SESSION['banner']; ?>" />
            </div>
            <p id="label-avatar" style="margin-top: 12px">Aktualny baner</p>
            <label for="banner">Wybierz nową grafikę z komputera</label>
            <input type="file" id="baner" name="banner">
            <label for="banner">Dopuszczalne formaty: JPG, PNG, SVG, GIF</label>
            <button class="btn btn-lg btn-primary" type="submit" disabled>Zmień</button><br /><br />
            <a id="link" href="controller/reset-baner-controller.php" style="font-weight: unset;"><i
                    class="bi bi-arrow-clockwise"></i>
                Zresetuj baner do domyślnego</a>
        </form>
        <div class="mb-3">
            <a id="link" href="profil" style="font-weight: unset;"><i class="bi bi-arrow-return-left"></i> Powrót na
                stronę profilu</a>
        </div>
    </div>
    <script src="script/buttonChangeAvatar.js" type="text/javascript"></script>
</body>

</HTML>