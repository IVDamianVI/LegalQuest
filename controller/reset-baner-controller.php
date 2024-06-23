<?php

declare(strict_types=1);
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../logowanie');
    exit();
}
require ('../access.php');

$user = $_SESSION['user'];

$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
if (!$dbConn) { //! Nie można połączyć z bazą danych
    $_SESSION['error_message'] = 'Błąd połączenia z bazą danych.';
    header('Location: ../zmien-baner');
    exit();
} else { //^ Zmiana nazwy pliku w bazie danych
    mysqli_query($dbConn, "SET NAMES 'utf8'");
    if (isset($user)) {
        $query = "UPDATE users SET banner = '_default_banner.png' WHERE BINARY username = '$user';";
        mysqli_query($dbConn, $query);
        $_SESSION['banner'] = '_default_banner.png';
        $_SESSION['success_message'] = 'Baner został zmieniony.';
        header('Location: ../zmien-baner');
        exit();
    }
}