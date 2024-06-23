<?php

declare(strict_types=1);
session_start();
require '../access.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function redirectToRegisterWithError($error)
{
    $_SESSION['error_message'] = $error;
    header('Location: /ick/rejestracja');
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
        header('Location: /ick/logowanie');
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
    require '../access.php';

    $mail = new PHPMailer(true);
    try {
        $token = bin2hex(random_bytes(32));

        $mail->isSMTP();
        $mail->Host = $mailHost;
        $mail->SMTPAuth = true;
        $mail->Username = $mailUsername;
        $mail->Password = $mailPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->CharSet = 'UTF-8';
        $mail->setFrom($mailFrom, $mailFromName);
        $mail->addReplyTo($mailReplyTo, $mailReplyToName);
        $mail->addAddress($email, $user);

        $mail->isHTML(true);
        $mail->Subject = "[$appName] Twoje konto zostało utworzone!";
        $mail->Body = "
        <!DOCTYPE html>
        <html lang='pl'>
        <head>
            <meta charset='UTF-8'>
            <title>Potwierdzenie rejestracji konta</title>
        </head>
        <body style='font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; color: #2c3440; max-width: 600px; margin: auto;'>
            <div>
                <header style='background-color: #19379c; padding: 20px; text-align: center; color: #ffffff;'>
                    <h1>Witaj w $appName!</h1>
                </header>
                <main style='padding: 20px; background-color: #fbfffb;'>
                    <h2>Cześć $user,</h2>
                    <p>Dziękujemy za utworzenie konta w $appName. Twoje konto zostało pomyślnie założone.</p>
                    <h3>Aktywacja konta</h3>
                    <p>Proszę aktywować swoje konto, klikając w poniższy przycisk:</p>
                    <p>
                        <a href='https://ivdamianvi.smallhost.pl/ick/aktywacja-konta?token=$token' 
                        style='background-color: #4ab7d4; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                        Aktywuj konto
                        </a>
                    </p>
                    <h3>Dane konta</h3>
                    <p>Login: $user</p>
                    <p>Email: $email</p>
                    <h3>Początek przygody</h3>
                    <p>Możesz już teraz korzystać z pełni możliwości aplikacji. Zaloguj się i odkryj, co dla Ciebie przygotowaliśmy!</p>
                    <h3>Potrzebujesz pomocy?</h3>
                    <p>Jeśli masz jakiekolwiek pytania, skontaktuj się z nami wysyłając e-mail na adres:<br/>
                        <a href='mailto:$mailReplyTo' style='color: #4ab7d4; text-decoration: none;'>$mailReplyTo</a>
                    </p>
                </main>
                <footer style='background-color: #2c3440; padding: 10px; text-align: center; color: #ffffff;'>
                    <p>Prosimy o nie odpowiadanie na tę wiadomość, została ona wygenerowana automatycznie.</p>
                    <p>
                        <a href='https://ivdamianvi.smallhost.pl/ick/polityka-prywatnosci' style='color: #4ab7d4; text-decoration: none;'>Polityka prywatności</a> | 
                        <a href='https://ivdamianvi.smallhost.pl/ick/regulamin' style='color: #4ab7d4; text-decoration: none;'>Regulamin serwisu</a>
                    </p>
                    <p>&copy; 2024 $appName. Wszelkie prawa zastrzeżone.</p>
                </footer>
            </div>
        </body>
        </html>
        ";
        $mail->AltBody = "
        Witaj w $appName!

        Cześć $user,

        Dziękujemy za utworzenie konta w $appName. Twoje konto zostało pomyślnie założone.

        Aktywacja konta
        Proszę aktywować swoje konto, klikając w link poniżej (lub skopiuj i wklej go do przeglądarki):
        https://ivdamianvi.smallhost.pl/ick/aktywacja-konta?token=$token

        Dane konta
        Login: $user
        Email: $email

        Początek przygody
        Możesz już teraz korzystać z pełni możliwości aplikacji. Zaloguj się i odkryj, co dla Ciebie przygotowaliśmy!

        Potrzebujesz pomocy?
        Jeśli masz jakiekolwiek pytania, skontaktuj się z nami wysyłając e-mail na adres: $mailReplyTo.

        Prosimy o nie odpowiadanie na tę wiadomość, została ona wygenerowana automatycznie.

        Polityka prywatności: https://ivdamianvi.smallhost.pl/ick/polityka-prywatnosci
        Regulamin serwisu: https://ivdamianvi.smallhost.pl/ick/regulamin

        © 2024 $appName. Wszelkie prawa zastrzeżone.
        ";

        $mail->send();
    } catch (Exception $e) {
        redirectToRegisterWithError('Błąd wysłania maila z danymi logowania.');
    }
}

$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
$pass = $_POST['pass'];
$pass1 = $_POST['pass1'];
$passHash = password_hash($pass, PASSWORD_BCRYPT);
$ftpAvatarDir = "/media/avatar";
$ftpBannerDir = "/media/banner";

if (!$dbConn) {
    redirectToRegisterWithError('Błąd połączenia z bazą danych.');
}

mysqli_query($dbConn, "SET NAMES 'utf8'");

if (!$user || !$pass || !$pass1) {
    redirectToRegisterWithError('Wszystkie pola muszą być wypełnione.');
}

if (!$email) {
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
        header('Location: /ick/logowanie');
        exit();
    } else {
        redirectToRegisterWithError(mysqli_error($dbConn));
    }
}
?>