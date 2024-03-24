<?php

declare(strict_types=1);
session_start();
if (!isset ($_SESSION['loggedin'])) {
    header('Location: logowanie.php');
    exit();
}
$_SESSION['page'] = basename($_SERVER['PHP_SELF']);
include ('controller/login-check-controller.php');
include ('controller/logout-auto-controller.php');
?>
<!DOCTYPE html>
<html lang="pl" data-bs-theme="dark">

<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Meta Info -->
    <meta name="author" content="Damian Grubecki">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <!-- Style Sheets Internal -->
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/style-page-main.css">
    <link rel="stylesheet" href="css/lightModeColors.css">
    <link rel="stylesheet" href="css/darkModeColors.css">
    <!-- Icon -->
    <link rel="icon" href="media/favicon/favicon-orange.png">
    <!-- Scripts Internal -->
    <script src="script/loadHeader.js"></script>
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
    <title>Strona Główna</title>
</head>

<body onload="myLoadHeader(); autoSubmitForm()">
    <div id='myHeader'></div>
    <main>
        <section class="sekcja1">
            <div class="container-fluid">
                <h1 class="text-center shine" style="color: var(--primary-color); padding-top: 6em;">
                    Witaj na stronie głównej!
                </h1>
            </div>
        </section>
    </main>
    <!-- Dark/Light Button START -->
    <button class="btn btn-outline-warning bg-dark position-fixed end-0 translate-middle-y" id="btnSwitch"
        style="z-index: 999; margin-right: 2px; border-color: var(--primary-color);">
        <i class="bi bi-sun-fill" style="color: var(--primary-color)"></i>
    </button>
    <script type="text/javascript" src="script/buttonTheme.js"></script>
    <!-- Dark/Light Button END -->
    <?php require_once 'footer.php'; ?>
    <!-- Get Info START -->
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
    <!-- Get Info END -->
</body>

</html>