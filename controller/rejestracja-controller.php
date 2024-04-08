<?php

declare(strict_types=1);
session_start();
require ('../access.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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

function createUserAndUploadAvatar($dbConn, $email, $user, $passHash, $fileName)
{
    $stmt = mysqli_prepare($dbConn, "INSERT INTO users (email, username, password, avatar) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssss', $email, $user, $passHash, $fileName);
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

function sendEmail($email, $user)
{
    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
    require ('../access.php');
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $mailHost;
        $mail->SMTPAuth = true;
        $mail->Username = $mailUsername;
        $mail->Password = $mailPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($mailFrom, $mailFromName);
        $mail->addReplyTo($mailReplyTo, $mailReplyToName);
        $mail->addAddress($email, $user);

        //Attachments
        // $mail->addAttachment('../media/favicon/favicon-orange.png', 'ikona.png');

        // Content
        $mail->isHTML(true);
        $mail->Subject = '[ICK] Twoje konto zostało utworzone!';
        $mail->Body = '
        <html>
        <head>
            <title>Platforma e-learningowa ICK - Informacja o nowym użytkowniku</title>
        </head>
        <body>
            <img src="https://ick.ivdamianvi.smallhost.pl/media/favicon/favicon.svg" height="40px" alt="Logo" title="Logo">
            <h1>Witaj!</h1>
            <p>Cześć ' . $user . ',</p>
            <br/>
            <p>Dziękujemy za założenie konta na ICK.</p>
            <br/>
            <p><b>Twoje dane:</b></p>
            <p>E-mail: ' . $email . '</p>
            <p>Nazwa użytkownika: ' . $user . '</p>
            <br/>
            <p>Administratorem twoich danych osobowych jest Damian Grubecki, który przetwarza je w celu realizacji usługi platformy e-learningowej ICK. Dane będą przetwarzane do czasu usunięcia konta użytkownika.</p>
        </body>
        </html>
        ';
        $mail->AltBody = 'Witaj! Na platformie e-learningowej ICK zarejestrował się nowy użytkownik. Imię i nazwisko: ' . $user . ' E-mail: ' . $email . ' Administratorem twoich danych osobowych jest Damian Grubecki, który przetwarza je w celu realizacji usługi platformy e-learningowej ICK. Dane będą przetwarzane do czasu usunięcia konta użytkownika.';

        $mail->send();
        echo 'Wiadomość została wysłana!';
    } catch (Exception $e) {
        redirectToRegisterWithError('Błąd wysłania maila z danymi logowania.');
    }
}

$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
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

if (!isset($user) || !isset($pass) || !isset($pass1)) {
    redirectToRegisterWithError('Wszystkie pola muszą być wypełnione.');
}

if (empty($email)) {
    redirectToRegisterWithError('Wprowadź poprawny adres e-mail.');
}

if ($pass !== $pass1) {
    redirectToRegisterWithError('Hasła nie są takie same.');
}

if (strlen($user) < 5) {
    redirectToRegisterWithError('Nazwa użytkownika musi mieć co najmniej 5 znaków.');
}

$stmt = mysqli_prepare($dbConn, "SELECT * FROM users WHERE username=?");
mysqli_stmt_bind_param($stmt, 's', $user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    redirectToRegisterWithError('Wprowadź inną nazwę użytkownika.');
}

$stmt = mysqli_prepare($dbConn, "SELECT * FROM users WHERE email=?");
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    redirectToRegisterWithError('Na podany adres e-mail jest już zarejestrowane konto.');
}

if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] === 0) {
    $avatar = $_FILES["avatar"]["tmp_name"];
    $fileName = $_FILES["avatar"]["name"];
    $ftpConn = connectToFtpServer($ftpServer, $ftpUsername, $ftpPassword);
    if (uploadFileToFtp($ftpConn, $ftpAvatarDir, $avatar, $fileName)) {
        createUserAndUploadAvatar($dbConn, $email, $user, $passHash, $fileName);
        sendEmail($email, $user);
    } else {
        ftp_close($ftpConn);
        redirectToRegisterWithError('Błąd przesyłania pliku na serwer FTP.');
    }
} else {
    $stmt = mysqli_prepare($dbConn, "INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sss', $email, $user, $passHash);
    mysqli_stmt_execute($stmt);
    if ($stmt) {
        sendEmail($email, $user);
        $_SESSION['success_message'] = 'Pomyślnie utworzono konto.';
        mysqli_close($dbConn);
        header('Location: ../logowanie.php');
        exit();
    } else {
        redirectToRegisterWithError(mysqli_error($dbConn));
    }
}
?>