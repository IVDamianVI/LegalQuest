<?php

declare(strict_types=1);
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
if (!isset ($_SESSION['loggedin'])) {
    header('Location: ../logowanie.php');
    exit();
}
require ('../access.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $avatar = $_FILES["avatar"]["tmp_name"];
    $fileName = $_FILES["avatar"]["name"];
    $allowedExtensions = ["jpg", "jpeg", "png", "svg", "gif"];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedExtensions)) {
        $_SESSION['error_message'] = 'Niedozwolone rozszerzenie pliku.';
        header('Location: ../zmien-avatar.php');
        exit();
    }
    $ftpConn = ftp_connect($ftpServer);
    if ($ftpConn) {
        $login = ftp_login($ftpConn, $ftpUsername, $ftpPassword);
        if ($login) {
            if (ftp_chdir($ftpConn, $ftpAvatarDir)) {
                if (ftp_put($ftpConn, $fileName, $avatar, FTP_BINARY)) {
                    $avatar = $_FILES['avatar']['name'];
                    $user = $_SESSION['user'];
                    $dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
                    if (!$dbConn) { //! Nie można połączyć z bazą danych
                        $_SESSION['error_message'] = 'Błąd połączenia z bazą danych.';
                        header('Location: ../zmien-avatar.php');
                        exit();
                    } else { //^ Zmiana nazwy pliku w bazie danych
                        mysqli_query($dbConn, "SET NAMES 'utf8'");
                        if (isset ($user) && isset ($fileName)) {
                            $query = "UPDATE users SET avatar = '$fileName' WHERE BINARY username = '$user';";
                            mysqli_query($dbConn, $query);
                            $_SESSION['avatar'] = $fileName;
                            $_SESSION['success_message'] = 'Avatar został zmieniony.';
                            header('Location: ../zmien-avatar.php');
                            exit();
                        }
                    }
                } else { //! Nie można przesłać pliku na serwer FTP
                    $_SESSION['error_message'] = 'Błąd przesyłania pliku na serwer FTP.';
                    header('Location: ../zmien-avatar.php');
                    exit();
                }
            } else { //! Nie można zmienić katalogu na serwerze FTP
                $_SESSION['error_message'] = 'Błąd zmiany katalogu na serwerze FTP.';
                header('Location: ../zmien-avatar.php');
                exit();
            }
            ftp_close($ftpConn);
        } else { //! Nie można zalogować się do serwera FTP
            $_SESSION['error_message'] = 'Błąd logowania do serwera FTP.';
            header('Location: ../zmien-avatar.php');
            exit();
        }
    } else { //! Nie można połączyć się z serwerem FTP
        $_SESSION['error_message'] = 'Błąd połączenia z serwerem FTP.';
        header('Location: ../zmien-avatar.php');
        exit();
    }
} else { //! Nieprawidłowe żądanie
    $_SESSION['error_message'] = 'Nieprawidłowe żądanie.';
    header('Location: ../zmien-avatar.php');
    exit();
}
mysqli_close($dbConn);
?>