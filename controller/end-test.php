<?php

declare(strict_types=1);
session_start();
require ('../access.php');

if (isset($_SESSION['test_id'])) {
    $test_id = $_SESSION['test_id'];

    $dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
    if ($dbConn) {
        $stmt = mysqli_prepare($dbConn, "UPDATE tests SET status='finished' WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'i', $test_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
    }

    unset($_SESSION['test_id']);
    unset($_SESSION['questions']);
    unset($_SESSION['time_option']);
    unset($_SESSION['max_time_per_question']);
    unset($_SESSION['max_time_test']);
}
?>