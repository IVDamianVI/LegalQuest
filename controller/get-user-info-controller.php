<?php
declare(strict_types=1);
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require ('../access.php');

    // Pozyskanie danych z IP
    function ip_details($ip)
    {
        $json = file_get_contents("http://ipinfo.io/{$ip}/geo");
        $details = json_decode($json);
        return $details;
    }
    $ipaddress = $_SERVER["REMOTE_ADDR"];
    $details = ip_details($ipaddress);

    // Ustawianie wartości dla przeglądarki i systemu operacyjnego
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    // Ustawianie wartości dla przeglądarki
    $browserInfo = "";
    $os = "";

    $browserInfoPattern = '/(Firefox|Chrome|Safari|Opera|Edge|Internet Explorer)[\/ ]([0-9.]+)/i';

    if (preg_match($browserInfoPattern, $userAgent, $browserInfoMatches)) {
        $browserInfo = $browserInfoMatches[1] . " " . $browserInfoMatches[2];
    }

    if (stripos($userAgent, 'OPR') !== false) {
        $operaPattern = '/OPR\/([0-9.]+)/';
        if (preg_match($operaPattern, $userAgent, $operaMatches)) {
            $browserInfo = "Opera " . $operaMatches[1];
        } else {
            $browserInfo = "Opera";
        }
    }
    // Ustawianie wartości dla systemu operacyjnego
    $osPattern = '/\(([^)]+)\)/';

    $osMapping = [
        "Windows NT 10" => "Windows 10",
        "Windows NT 6.3" => "Windows 8.1",
        "Windows NT 6.2" => "Windows 8",
        "Windows NT 6.1" => "Windows 7",
        "Windows NT 6.0" => "Windows Vista",
        "Windows NT 5.1" => "Windows XP",
        "Android 13" => "Android 13",
        "Android 12" => "Android 12",
        "Android 11" => "Android 11",
        "Android 10" => "Android 10",
        "Android 9" => "Android 9",
        "Android 8" => "Android 8",
        "Android 7" => "Android 7",
    ];

    if (preg_match($osPattern, $userAgent, $osMatches)) {
        $fullOs = $osMatches[1];
        $os = $fullOs;

        foreach ($osMapping as $key => $value) {
            if (stripos($fullOs, $key) !== false) {
                $os = $value;
                break;
            }
        }

        if ($os === $fullOs) {
            if (preg_match('/OS (\d+_\d+)/', $fullOs, $matches)) {
                $versionWithUnderscore = $matches[1];
                $versionWithDot = str_replace('_', '.', $versionWithUnderscore);
                $os = "iOS " . $versionWithDot;
            }
        }
    } else {
        $os = $fullOs;
    }

    // Skracanie nazwy regionu
    if ($details->region == "Kujawsko-Pomorskie") {
        $region = "Kuj-Pom";
    } else {
        $region = $details->region;
    }

    // Przypisywanie wartości do zmiennych
    $ip = $details->ip;
    $city = $_POST['city'];
    if ($city == '' || $city == 'undefined' || $city == 'your city') {
        $localization = $details->country . ", " . $region . ", " . $details->city;
        $coord = $details->loc;
    } else {
        $localization = $details->country . ", " . $_POST['city'];
        $coord = $_POST['coords'];
    }

    $browser = $browserInfo . " " . $os;
    $display = $_POST['display'];
    $viewport = $_POST['viewport'];
    $colors = $_POST['colors'];
    $cookies = $_POST['cookies'];
    $java = $_POST['java'];
    $page = $_POST['page'];
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 3, 2);
    $userDevice = exec('whoami');

    if (!isset ($_SESSION['user'])) {
        $user = '-';
    } else {
        $user = $_SESSION['user'];
    }

    // Zapis w bazie danych
    // if ($ip != '') {
    if ($ip != '83.21.255.7' && $ip != '' && $ip != '83.21.250.229') {
        $dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);

        if ($dbConn) {
            $query = "INSERT INTO goscieportalu (page, username, ip, userdevice, localization, coord, browser, display, viewport, colors, cookies, java, lang) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($dbConn, $query);

            mysqli_stmt_bind_param($stmt, "sssssssssssss", $page, $user, $ip, $userDevice, $localization, $coord, $browser, $display, $viewport, $colors, $cookies, $java, $lang);

            if (mysqli_stmt_execute($stmt)) {
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_close($dbConn);
    }
}
