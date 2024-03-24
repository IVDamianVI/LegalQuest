<?php

declare(strict_types=1);
session_start();
require ('../access.php');

function redirectToRegisterWithError($error)
{
    $_SESSION['error_message'] = $error;
    header('Location: ../rejestracja.php');
    exit();
}

function connectToFtpServer($ftpServer, $ftpUsername, $ftpPassword)
{
    $ftpConn = ftp_connect($ftpServer);
    if (!$ftpConn) {
        redirectToRegisterWithError('Błąd połączenia z serwerem FTP.');
    }
    $ftpLogin = ftp_login($ftpConn, $ftpUsername, $ftpPassword);
    if (!$ftpLogin) {
        ftp_close($ftpConn);
        redirectToRegisterWithError('Błąd logowania do serwera FTP.');
    }
    return $ftpConn;
}

function uploadFileToFtp($ftpConn, $ftpDir, $localFilePath, $remoteFileName)
{
    if (ftp_chdir($ftpConn, $ftpDir) && ftp_put($ftpConn, $remoteFileName, $localFilePath, FTP_BINARY)) {
        return true;
    }
    return false;
}

function createUserAndUploadAvatar($dbConn, $user, $passHash, $fileName)
{
    $stmt = mysqli_prepare($dbConn, "INSERT INTO users (username, password, avatar) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sss', $user, $passHash, $fileName);
    mysqli_stmt_execute($stmt);
    if ($stmt) {
        $_SESSION['success_message'] = 'Pomyślnie utworzono konto.';
        mysqli_close($dbConn);
        header('Location: ../logowanie.php');
        exit();
    } else {
        redirectToRegisterWithError('Błąd bazy danych.');
    }
}

$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
$user = htmlentities($_POST['user'], ENT_QUOTES, "UTF-8");
$pass = $_POST['pass'];
$pass1 = $_POST['pass1'];
$passHash = password_hash($pass, PASSWORD_BCRYPT);
$ftpAvatarDir = "/media/avatar";
$ftpBannerDir = "/media/banner";
if (!$dbConn) {
    redirectToRegisterWithError('Błąd połączenia z bazą danych.');
}
mysqli_query($dbConn, "SET NAMES 'utf8'");
if (!isset ($user) || !isset ($pass) || !isset ($pass1)) {
    redirectToRegisterWithError('Wszystkie pola muszą być wypełnione.');
}
$stmt = mysqli_prepare($dbConn, "SELECT * FROM users WHERE username=?");
mysqli_stmt_bind_param($stmt, 's', $user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    redirectToRegisterWithError('Wprowadź inną nazwę użytkownika.');
}
if (isset ($_FILES["avatar"]) && $_FILES["avatar"]["error"] === 0) {
    $avatar = $_FILES["avatar"]["tmp_name"];
    $fileName = $_FILES["avatar"]["name"];
    $ftpConn = connectToFtpServer($ftpServer, $ftpUsername, $ftpPassword);
    if (uploadFileToFtp($ftpConn, $ftpAvatarDir, $avatar, $fileName)) {
        createUserAndUploadAvatar($dbConn, $user, $passHash, $fileName);
    } else {
        ftp_close($ftpConn);
        redirectToRegisterWithError('Błąd przesyłania pliku na serwer FTP.');
    }
} else {
    $stmt = mysqli_prepare($dbConn, "INSERT INTO users (username, password) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'ss', $user, $passHash);
    mysqli_stmt_execute($stmt);
    if ($stmt) {
        $_SESSION['success_message'] = 'Pomyślnie utworzono konto.';
        mysqli_close($dbConn);
        header('Location: ../logowanie.php');
        exit();
    } else {
        redirectToRegisterWithError(mysqli_error($dbConn));
    }
}
?>