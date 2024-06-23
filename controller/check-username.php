<?php

require ('../access.php');

if (isset($_POST['username'])) {
    $user = $_POST['username'];

    $dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
    $stmt = mysqli_prepare($dbConn, "SELECT * FROM users WHERE username=?");
    mysqli_stmt_bind_param($stmt, 's', $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['available' => false]);
    } else {
        echo json_encode(['available' => true]);
    }

    mysqli_close($dbConn);
}

?>