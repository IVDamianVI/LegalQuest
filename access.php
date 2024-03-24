<?php

// Dane dostępowe do bazy danych
$dbHost = "mysql1.small.pl";
$dbUsername = "m1932_ick";
$dbPassword = "Ick@2137";
$dbDatabase = "m1932_ick";

// Dane dostępowe do folderu FTP
$ftpServer = "s1.small.pl";
$ftpUsername = "f1932_ick";
$ftpPassword = "Ick@2137";
$ftpUsersDir = "/media/users";
$ftpAvatarDir = "/media/avatar";
$ftpBannerDir = "/media/banner";
$ftpSongDir = "/media/song";
$ftpFilmDir = "/media/film";
if (isset ($_SESSION['user'])) {
    $ftpCloudDir = "/media/cloud/" . $_SESSION['user'];
}


// Funkcja logowania
$zadanie = 'ick';

?>