<?php
declare(strict_types=1);
session_start();
require ('../access.php');

if (isset($_SESSION['block_time']) && time() - $_SESSION['block_time'] < 60) {
    $remainingTime = 60 - (time() - $_SESSION['block_time']);
    $_SESSION['error_message'] = "Za dużo nieudanych prób.<br/> Zaczekaj $remainingTime sekund przed ponowną próbą.";
    if (isset($_SESSION['login_attempts'])) {
        unset($_SESSION['login_attempts']);
    }
    header('Location: /ick/logowanie');
    exit();
}

if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
    logUnsuccessfulAttempt($_POST['user'], $_SERVER['REMOTE_ADDR']);

    $_SESSION['error_message'] = 'Za dużo nieudanych prób.<br/> Zaczekaj 1 minutę przed ponowną próbą.';
    $_SESSION['block_time'] = time();
    header('Location: /ick/logowanie');
    exit();
}

$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
$user = htmlentities($_POST['user'], ENT_QUOTES, "UTF-8");
$pass = $_POST['pass'];
$wrongPass = 'Nieprawidłowa nazwa użytkownika/hasło!';

if ($dbConn) {
    mysqli_query($dbConn, "SET NAMES 'utf8'");

    $stmt = mysqli_prepare($dbConn, "SELECT * FROM users WHERE BINARY username=?");
    mysqli_stmt_bind_param($stmt, 's', $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($usersArray = mysqli_fetch_array($result)) {
        $passHash = $usersArray['password'];

        if (password_verify($pass, $passHash)) {
            $stmt = mysqli_prepare($dbConn, "SELECT * FROM users WHERE BINARY username=?");
            mysqli_stmt_bind_param($stmt, 's', $user);
            mysqli_stmt_execute($stmt);
            $userAssoc = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

            $_SESSION['loggedin'] = true;
            $_SESSION['user'] = $user;
            $_SESSION['avatar'] = $userAssoc['avatar'];
            $_SESSION['page'] = 'index.php';
            $_SESSION['user_id'] = $userAssoc['id'];
            $_SESSION['created'] = $userAssoc['created'];
            $_SESSION['banner'] = $userAssoc['banner'];
            $_SESSION['userGroup'] = $userAssoc['userGroup'];

            if (isset($_SESSION['login_attempts'])) {
                unset($_SESSION['login_attempts']);
            }
            if (isset($_SESSION['block_time'])) {
                unset($_SESSION['block_time']);
            }

            logUnsuccessfulAttempt($user, $_SERVER['REMOTE_ADDR']);

            header("Location: /ick/");
        } else {
            $_SESSION['error_message'] = $wrongPass;

            if (!isset($_SESSION['login_attempts'])) {
                $_SESSION['login_attempts'] = 1;
            } else {
                $_SESSION['login_attempts']++;
            }

            header('Location: /ick/logowanie');
            exit();
        }
    } else {
        $_SESSION['error_message'] = $wrongPass;
        header('Location: /ick/logowanie');
        exit();
    }
} else {
    $_SESSION['error_message'] = 'Błąd połączenia z bazą danych.';
    header('Location: /ick/logowanie');
    exit();
}

mysqli_close($dbConn);

function logUnsuccessfulAttempt($username, $ip)
{
    require ('../access.php');
    $dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
    $stmt = mysqli_prepare($dbConn, "INSERT INTO break_ins (username, ip) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'ss', $username, $ip);
    mysqli_stmt_execute($stmt);
}


?>