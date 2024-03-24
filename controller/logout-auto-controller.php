<?php
declare(strict_types=1);
session_start();
function checkUserActivity()
{
    $inactive_timeout = (1 * 24 * 60 * 60);
    if (isset ($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive_timeout)) {
        session_unset();
        session_destroy();
        header("Location: ../logowanie.php");
        exit();
    }
    $_SESSION['last_activity'] = time();
}
checkUserActivity();
?>