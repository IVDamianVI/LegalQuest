<?php declare(strict_types=1);
session_start();
include ('access.php');
if (isset ($_SESSION['loggedin'])) {
    session_start();
    session_unset();
    header("Location: ../$zadanie/");
}
?>