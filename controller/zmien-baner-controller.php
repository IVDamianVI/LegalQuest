<?php

declare(strict_types=1);
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../logowanie');
    exit();
}
require ('../access.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $banner = $_FILES["banner"]["tmp_name"];
    $fileName = $_FILES["banner"]["name"];
    $allowedExtensions = ["jpg", "jpeg", "png", "svg", "gif"];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedExtensions)) {
        $_SESSION['error_message'] = 'Niedozwolone rozszerzenie pliku.';
        header('Location: ../zmien-baner');
        exit();
    }
    $ftpConn = ftp_connect($ftpServer);
    if ($ftpConn) {
        $login = ftp_login($ftpConn, $ftpUsername, $ftpPassword);
        if ($login) {
            if (ftp_chdir($ftpConn, $ftpBannerDir)) {
                if (ftp_put($ftpConn, $fileName, $banner, FTP_BINARY)) {
                    $banner = $_FILES['banner']['name'];
                    $user = $_SESSION['user'];
                    $dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
                    if (!$dbConn) { //! Nie można połączyć z bazą danych
                        $_SESSION['error_message'] = 'Błąd połączenia z bazą danych.';
                        header('Location: ../zmien-baner');
                        exit();
                    } else { //^ Zmiana nazwy pliku w bazie danych
                        mysqli_query($dbConn, "SET NAMES 'utf8'");
                        if (isset($user) && isset($fileName)) {
                            $query = "UPDATE users SET banner = '$fileName' WHERE BINARY username = '$user';";
                            mysqli_query($dbConn, $query);
                            $_SESSION['banner'] = $fileName;
                            $_SESSION['success_message'] = 'Baner został zmieniony.';
                            header('Location: ../zmien-baner');
                            exit();
                        }
                    }
                } else { //! Nie można przesłać pliku na serwer FTP
                    $_SESSION['error_message'] = 'Błąd przesyłania pliku na serwer FTP.';
                    header('Location: ../zmien-baner');
                    exit();
                }
            } else { //! Nie można zmienić katalogu na serwerze FTP
                $_SESSION['error_message'] = 'Błąd zmiany katalogu na serwerze FTP.';
                header('Location: ../zmien-baner');
                exit();
            }
            ftp_close($ftpConn);
        } else { //! Nie można zalogować się do serwera FTP
            $_SESSION['error_message'] = 'Błąd logowania do serwera FTP.';
            header('Location: ../zmien-baner');
            exit();
        }
    } else { //! Nie można połączyć się z serwerem FTP
        $_SESSION['error_message'] = 'Błąd połączenia z serwerem FTP.';
        header('Location: ../zmien-baner');
        exit();
    }
} else { //! Nieprawidłowe żądanie
    $_SESSION['error_message'] = 'Nieprawidłowe żądanie.';
    header('Location: ../zmien-baner');
    exit();
}
mysqli_close($dbConn);
?>