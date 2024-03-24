<?php
declare(strict_types=1);
session_start();
require ('access.php');
$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
$username = $_SESSION['user'];
$result = mysqli_query($dbConn, 'SELECT * FROM users WHERE username = "' . $username . '"');
if (mysqli_fetch_assoc($result) == 0) {
    $_SESSION['error_message'] = $wrongPass;
    header('Location: logout-controller.php');
    exit();
}
?>