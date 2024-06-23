<?php declare(strict_types=1);
session_start();
require ('../access.php');
$isFooterFixedBottom = true;
if
($_SESSION['loggedin'] == true) {
    if ($_SESSION['userGroup'] == 'admin') {
        $dbConn = mysqli_connect(
            $dbHost,
            $dbUsername,
            $dbPassword,
            $dbDatabase
        );
        if (!$dbConn) {
            die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
        }
    } else {
        header(' Location: /ick/');
        exit();
    }
} else {
    header('Location: /ick/');
    exit();
} ?>

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
    <title>Dodaj pytanie | Admin Panel | <?php echo $appName; ?></title>
    <style>
        th {
            background-color: var(--primary-color) !important;
            color: #000 !important;
        }
    </style>
</head>

<body>
    <main class="container" style="margin-top: 10px;">
        <a href="panel.php" class="link">
            <p class="text-center">
                <i class="bi bi-arrow-return-left"></i> Wróć na stronę panelu
            </p>
        </a>
        <h1 class="text-center">Lista pytań</h1>
        <hr />
        <table id="questions" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pytanie</th>
                    <th>Poprawna odpowiedź</th>
                    <th>Odpowiedź B</th>
                    <th>Odpowiedź C</th>
                    <th>Kategoria</th>
                    <th>Podkategoria</th>
                    <th>Komentarz</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
                $result = mysqli_query($dbConn, "SELECT * FROM questions");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['question'] . '</td>';
                    echo '<td>' . $row['answer_a'] . '</td>';
                    echo '<td>' . $row['answer_b'] . '</td>';
                    echo '<td>' . $row['answer_c'] . '</td>';
                    echo '<td>' . $row['category_id'] . '</td>';
                    echo '<td>' . $row['subcategory_id'] . '</td>';
                    echo '<td>' . $row['comment'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </main>
    <?php include_once '../footer.php'; ?>
</body>

</html>