<?php

declare(strict_types=1);
session_start();
// if (!isset($_SESSION['loggedin'])) {
//     header('Location: logowanie.php');
//     exit();
// }
$_SESSION['page'] = basename($_SERVER['PHP_SELF']);

require ('access.php');
$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
if (isset ($_GET['user'])) {
    $username = $_GET['user'];
} else if (isset ($_SESSION['user'])) {
    $username = $_SESSION['user'];
} else {
    $username = 'Gość';
    $banner = '_default_banner.png';
    $avatar = '_default_avatar.svg';
    $created = '<a href="rejestracja.php" style="color: var(--primary-color);">Zarejestruj się</a>';
}

if (isset ($_SESSION['user'])) {
    if ($username != $_SESSION['user']) {
        $show = 'display: none;';
        $showAvatar = false;
    } else {
        $show = '';
        $showAvatar = true;
    }
} else if ($username == 'Gość') {
    $show = 'display: none;';
    $showAvatar = false;
} else {
    $show = '';
    $showAvatar = true;
}

$result = mysqli_query($dbConn, "SELECT * FROM users WHERE username = '$username'");
while ($row = mysqli_fetch_assoc($result)) {
    $created = $row['created'];
    $avatar = $row['avatar'];
    $banner = $row['banner'];

    $created = str_replace("-", ".", $created);

    list($data, $czas) = explode(" ", $created);

    list($rok, $miesiac, $dzien) = explode(".", $data);
    $data_polish = $dzien . "." . $miesiac . "." . $rok;

    $created = $data_polish . " " . $czas;
}

// include('controller/login-check-controller.php');
// include('controller/logout-auto-controller.php');

// function ip_details($ip)
// {
//     if(file_get_contents("http://ipinfo.io/{$ip}/geo")) {
//         $json = file_get_contents("http://ipinfo.io/{$ip}/geo");
//         $details = json_decode($json);
//         return $details;
//     }
// }

// $countryCodes = [
//     'PL' => 'Polska',
//     'US' => 'Stany Zjednoczone',
//     'DE' => 'Niemcy',
//     'FR' => 'Francja',
//     'ES' => 'Hiszpania',
//     'IT' => 'Włochy',
//     'GB' => 'Wielka Brytania',
//     'CA' => 'Kanada',
//     'AU' => 'Australia',
//     'JP' => 'Japonia',
//     'CN' => 'Chiny',
//     'IN' => 'Indie',
//     'BR' => 'Brazylia',
//     'RU' => 'Rosja',
// ];

// $countryCode = ip_details($_SERVER["REMOTE_ADDR"])->country;

// if (array_key_exists($countryCode, $countryCodes)) {
//     $countryName = $countryCodes[$countryCode];
// } else {
//     $countryName = $countryCode;
// }
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
    <meta name="description" content="">
    <meta name="keywords" content="">
    <!-- Style Sheets Internal -->
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/style-page-main.css">
    <link rel="stylesheet" href="css/style-page-profile.css">
    <link rel="stylesheet" href="css/lightModeColors.css">
    <link rel="stylesheet" href="css/darkModeColors.css">
    <!-- GeoIP2 -->
    <script src="//geoip-js.com/js/apis/geoip2/v2.1/geoip2.js"></script>
    <!-- Icon -->
    <link rel="icon" href="media/favicon/favicon-orange.png">
    <!-- Scripts Internal -->
    <script src="script/loadHeader.js"></script>
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
    <title>Profil Użytkownika</title>

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
    </style>
</head>

<body onload="myLoadHeader(); autoSubmitForm()">
    <div id='myHeader'></div>
    <main>
        <section class="sekcja1" data-bs-theme="dark">
            <div class="container">
                <div class="container banner"
                    style='background-color: #000000; background-image: url("media/banner/<?php echo $banner; ?>"); background-size: cover; background-repeat: no-repeat; background-position: center center; box-shadow: inset 0px -140px 140px -70px #000000;'>
                    <a href="zmien-baner.php" style="<?php echo $show; ?>">
                        <span id="changeBannerIcon"><i class="bi bi-image"></i></span>
                    </a>
                    <div class="container banner-inside" style="padding: 5px; padding-top: 6em; padding-bottom: 10px;">
                        <div class="row align-items-end">
                            <div class="col avatarImage" style="max-width: 12.5em !important;">
                                <?php if ($showAvatar): ?>
                                    <a id="changeAvatar" href="zmien-avatar.php">
                                        <span id="changeAvatarIcon"><i class="bi bi-person-bounding-box"></i></span>
                                        <img id="changeAvatarImg" src="media/avatar/<?php echo $avatar; ?>" alt="Avatar"
                                            style="background-color: #161616; width: 12em; height: 12em; margin: 0; padding: 0;" />
                                    </a>
                                <?php endif; ?>
                                <?php if (!$showAvatar): ?>
                                    <span id="changeAvatarIcon"><i class="bi bi-person-bounding-box"></i></span>
                                    <img id="changeAvatarImg" src="media/avatar/<?php echo $avatar; ?>" alt="Avatar"
                                        style="background-color: #161616; width: 12em; height: 12em; margin: 0; padding: 0;" />
                                <?php endif; ?>
                            </div>
                            <div class="col" style="text-align: left;">
                                <span style="color: #ffffff; font-weight:bold; font-size: 2em;">
                                    <?php echo $username; ?>
                                </span><br>
                                <!-- <span style="color: #ffffff; font-size: 1em;"><?php //echo $countryName                    ?></span><br> -->
                                <span style="color: #ffffff; font-size: 1em;">Utworzono:
                                    <?php echo $created ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
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