<?php

declare(strict_types=1);
session_start();
require ('../access.php');

$isFooterFixedBottom = true;

if ($_SESSION['loggedin'] == true) {
    if ($_SESSION['userGroup'] == 'admin') {
        $dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
        if (!$dbConn) {
            die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
        }
    } else {
        header('Location: /ick/');
        exit();
    }
} else {
    header('Location: /ick/');
    exit();
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
    <meta name="keywords" content="admin, add question, quiz">
    <link rel="stylesheet" href="/ick/css/colors.css">
    <link rel="stylesheet" href="/ick/css/style-page-main.css">
    <link rel="stylesheet" href="/ick/css/style-page-kategoria.css">
    <link rel="stylesheet" href="/ick/css/lightModeColors.css">
    <link rel="stylesheet" href="/ick/css/darkModeColors.css">
    <link rel="icon" href="<?php echo $faviconSRC; ?>">
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
    <title>Admin Panel | <?php echo $appName; ?></title>

    <style>
        .admin-buttons h1 {
            font-size: 4em;
            font-weight: bold;
            font-variant: small-caps;
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
        }

        .admin-buttons a {
            position: relative;
            text-decoration: none !important;
            font-weight: bold;
            cursor: default;
        }

        .admin-buttons p {
            margin: 0em;
        }

        .btn {
            margin: 1em;
            font-size: 1.5em;
        }

        .admin-buttons .btn-success {
            min-width: 160px !important;
            width: 200px !important;
            color: #000;
            height: 150px;
            background-color: var(--primary-color);
            border: none;
            border-radius: 10%;
            transition: all .3s;
        }

        .admin-buttons .btn-success:hover {
            color: var(--primary-color);
            background-color: white;
            border: none;
            box-shadow: 0px 0px 40px 0px white;
            font-weight: bold;
            transition: all .3s;
            scale: 1.1;
        }

        .admin-buttons .btn-success i {
            font-size: 2.5em;
        }

        .card-header {
            background-color: var(--primary-color);
            color: #000;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <main class="container" style="margin-top: 10px;">
        <h1 class="text-center">Panel administracyjny</h1>
        <hr />
        <div class="row">
            <div class="col-2">
                <div class="card text-white card-klienci mb-3">
                    <div class="card-header"><i class="bi bi-person-fill"></i> Użytkownicy</div>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <?php
                            $sql = 'SELECT COUNT(*) AS count FROM users WHERE userGroup = "user"';
                            $result = mysqli_query($dbConn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            echo $row['count'];
                            ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="card text-white card-klienci mb-3">
                    <div class="card-header"><i class="bi bi-clipboard2-fill"></i> Testy</div>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <?php
                            $sql = 'SELECT COUNT(*) AS count FROM tests WHERE end_date IS NOT NULL';
                            $result = mysqli_query($dbConn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            echo $row['count'];
                            ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="card text-white card-klienci mb-3">
                    <div class="card-header"><i class="bi bi-person-badge-fill"></i> Moderatorzy</div>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <?php
                            $sql = 'SELECT COUNT(*) AS count FROM users WHERE userGroup = "admin"';
                            $result = mysqli_query($dbConn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            echo $row['count'];
                            ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="card text-white card-klienci mb-3">
                    <div class="card-header"><i class="bi bi-question-lg"></i> Pytania</div>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <?php
                            $sql = 'SELECT COUNT(*) AS count FROM questions';
                            $result = mysqli_query($dbConn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            echo $row['count'];
                            ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="card text-white card-klienci mb-3">
                    <div class="card-header"><i class="bi bi-list-ul"></i> Kategorie</div>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <?php
                            $sql = 'SELECT COUNT(*) AS count FROM category';
                            $result = mysqli_query($dbConn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            echo $row['count'];
                            ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class="row admin-buttons">
            <div class="col">
                <a href="list-question.php">
                    <button class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        data-bs-html="true" data-bs-title="<b>Lista miejsc</b><br/>Dodaj, edytuj<br/>lub usuń miejsca">
                        <i class="bi bi-list-ul"></i><br />
                        Lista pytań
                    </button>
                </a>
            </div>
            <div class="col">
                <a href="add-question.php">
                    <button class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        data-bs-html="true" data-bs-title="<b>Lista miejsc</b><br/>Dodaj, edytuj<br/>lub usuń miejsca">
                        <i class="bi bi-question-lg"></i><br />
                        Dodaj pytanie
                    </button>
                </a>
            </div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
        </div>

    </main>
    <?php include_once '../footer.php'; ?>
</body>

</html>