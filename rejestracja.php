<?php

declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="pl" data-bs-theme="dark">

<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Meta Info -->
    <meta name="author" content="Damian Grubecki, Maciej Ludwiczak">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <!-- Style Sheets Internal -->
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/style-page-form.css">
    <!-- GeoIP2 -->
    <script src="//geoip-js.com/js/apis/geoip2/v2.1/geoip2.js"></script>
    <!-- Icon -->
    <link rel="icon" href="media/favicon/favicon-orange.png">
    <!-- Bootstrap CSS -->
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
    <title>Rejestracja</title>
</head>

<body onload="autoSubmitForm()" class="text-center background-first">
    <div class="form-register background-second">
        <div class="logo">
            <img height="60px" id="logo" src="media/favicon/favicon.svg" alt="Logo" title="Logo">
        </div>
        <h1 id="title">Zarejestruj się</h1>
        <span id="subtitle">Stwórz dla siebie nowe konto</span>
        <form class="form-signin form-register" method="post" action="controller/rejestracja-controller.php"
            enctype="multipart/form-data" autocomplete="off">
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
            <!--// TODO Sprawdzanie dostępności maila \\-->
            <span id="emailAvailability" class="alert-danger"></span>
            <div class="form-floating">
                <input type="text" class="form-control" id="email" minlength="3" placeholder="Email" name="email"
                    oninput="validateEmail(this)">
                <label for="email">E-mail</label>
            </div>
            <span id="usernameAvailability" class="alert-danger"></span>
            <div class="form-floating">
                <input type="text" class="form-control" id="username" minlength="8" placeholder="Login" name="user"
                    oninput="sanitizeUsername(this)">
                <label for="username">Nazwa użytkownika (min. 5 znaków)</label>
            </div>
            <div id="passwordStrengthBar">
                <div id="strengthIndicator"></div>
            </div>
            <div id="passwordFeedback"></div>
            <div class="form-floating">
                <input type="password" class="form-control" id="pass" minlength="8" placeholder="Hasło" name="pass"
                    onfocus="showPasswordStrength(true)" onblur="showPasswordStrength(false)"
                    oninput="checkPasswordStrength()">
                <label for="pass">Hasło (min. 8 znaków)</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="pass1" placeholder="Powtórz hasło" name="pass1">
                <label for="pass1" id="passwordLabel">Powtórz hasło</label>
            </div>
            <label for="avatar">Avatar (Opcjonalne)</label>
            <input type="file" id="avatar" name="avatar">
            <label>Dopuszczalne formaty: JPG, PNG, SVG, GIF</label>
            <button class="btn btn-lg btn-primary" type="submit" disabled>Zarejestruj się</button>
        </form>
        <div class="mb-3">
            <span class="color-text-icon">Masz już konto? <a id="link" href="logowanie.php">Zaloguj się</a> tutaj</span>
            <br>
            <a id="link" href="/" style="font-weight: unset;">Wróć na stronę główną</a>
        </div>
    </div>
    <script src="script/register.js" type="text/javascript"></script>
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

</HTML>