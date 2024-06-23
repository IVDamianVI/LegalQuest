<?php

declare(strict_types=1);
session_start();
if (isset($_SESSION['loggedin'])) {
    $user = $_SESSION['user'];
    $userGroup = $_SESSION['userGroup'];
    $avatar = $_SESSION['avatar'];
} else {
    $userGroup = 'guest';
    $user = 'Gość';
    $avatar = '_default_avatar.svg';
}

require ('access.php');

?>

<head>
    <link rel="stylesheet" href="/ick/css/style-header.css">
</head>
<header>
    <nav id="navbar" class="navbar navbar-expand-lg navbar-dark fixed-top hidden">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarButtons"
                aria-controls="navbarButtons" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list" style="font-size: 1em;"></i>
            </button>
            <a class="navbar-brand" href="/ick/">
                <img src="<?php echo $logoSRC; ?>" id="logo" alt="Logo <?php echo $appName; ?>"
                    style="margin-top: -1px; margin-left: 2px;" title="Logo <?php echo $appName; ?>" />
                <span id="app-name"><?php //echo $appName; ?></span>
            </a>
            <div class="mobile-only">
                <?php include 'account-circle.php'; ?>
            </div>
            <div class="collapse navbar-collapse align-items-center" id="navbarButtons" toggle="collapse"
                data-target=".navbar-collapse">
                <ul class="navbar-nav align-items-center">
                    <?php
                    $dbConnection = new mysqli($dbHost, $dbUsername, $dbPassword, $dbDatabase);
                    $dbConnection->set_charset('utf8');
                    $sql = "SELECT * FROM category WHERE id = 1";
                    $result = $dbConnection->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<li class="nav-item';
                            if ($_SESSION['page'] == $row['url_name'] . '.php') {
                                echo ' wybrana-strona';
                            }
                            echo '">';
                            echo '<a class="nav-link" href="/ick/kategoria/' . $row['url_name'] . '">' . $row['name'] . '</a>';
                            echo '</li>';
                        }
                    }
                    ?>
                    <li class="nav-item dropdown <?php if ($_SESSION['page'] == 'netstat.php' || $_SESSION['page'] == 'skrypty.php' || $_SESSION['page'] == 'geolocation.php')
                        echo 'wybrana-strona'; ?>">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Prawo</a>
                        <ul class="dropdown-menu">
                            <?php
                            $dbConnection = new mysqli($dbHost, $dbUsername, $dbPassword, $dbDatabase);
                            $dbConnection->set_charset('utf8');
                            $sql = "SELECT * FROM category WHERE id = 2 OR id = 3 OR id = 4 OR id = 5 OR id = 6 OR id = 7 OR id = 8";
                            $result = $dbConnection->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $imgIcon = "<img src='/ick/media/icon/" . $row['icon'] . "' alt='" . $row['name'] . "' style='height: 25px; margin-right: 5px; margin-top: -2px; margin-bottom: -2px;' />";
                                    echo '<li><a class="dropdown-item" href="/ick/kategoria/' . $row['url_name'] . '">' . $imgIcon . ' ' . $row['name'] . '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                    $dbConnection = new mysqli($dbHost, $dbUsername, $dbPassword, $dbDatabase);
                    $dbConnection->set_charset('utf8');
                    $sql = "SELECT * FROM category WHERE id = 9";
                    $result = $dbConnection->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<li class="nav-item';
                            if ($_SESSION['page'] == $row['url_name'] . '.php') {
                                echo ' wybrana-strona';
                            }
                            echo '">';
                            echo '<a class="nav-link" href="/ick/kategoria/' . $row['url_name'] . '">' . $row['name'] . '</a>';
                            echo '</li>';
                        }
                    }
                    ?>
                    <li class="nav-item <?php if ($_SESSION['page'] == 'o-projekcie.php')
                        echo 'wybrana-strona'; ?>">
                        <a class="nav-link" href="/ick/o-projekcie">O projekcie</a>
                    </li>
                </ul>
            </div>
            <div class="pc-only" style="<?php if (!isset($_SESSION['loggedin'])) {
                echo 'display: none;';
            } ?>">
                <div class="dropdown">
                    <img src="/ick/media/avatar/<?php echo $avatar; ?>" alt="Avatar"
                        style="margin-top: -1px; margin-left: 2px;" id="account" data-bs-toggle="dropdown"
                        class="mx-auto rounded-circle img-end d-block" />
                    <ul class="dropdown-menu dropdown-menu-end mt-2 mb-2"
                        style="background-color: var(--footer-bg-color-2) !important; border: 2px solid var(--footer-bg-color-1); border-radius: 15px !important;">
                        <li style="margin-left: 15px; margin-right: 15px; margin-top: 15px;">
                            <p style="font-size: 1.5em; font-weight: bold; line-height: 1px; margin: 0; padding: 0;">
                                <?php echo $user; ?>
                            </p><br>
                            <a href="/ick/profil/<?php echo $_SESSION['user_id']; ?>" id="showProfile" class="text-top"
                                style="line-height: 0px; margin: 0; padding: 0;">
                                <p>Zobacz profil</p>
                            </a>
                        </li>
                        <li style="margin-left: 15px; margin-right: 15px;">
                            <a class="dropdown-item" href="/ick/profil#achievements">
                                <i class="bi bi-trophy-fill"></i> Osiągnięcia</a>
                        </li>
                        <li style="margin-left: 15px; margin-right: 15px;">
                            <a class="dropdown-item" href="/ick/zmien-avatar">
                                <i class="bi bi-image"></i> Zmień avatar</a>
                        </li>
                        <li style="margin-left: 15px; margin-right: 15px;">
                            <a class="dropdown-item" href="controller/logout-controller.php">
                                <i class="bi bi-box-arrow-right"></i> Wyloguj się</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="pc-only" style="<?php if (isset($_SESSION['loggedin'])) {
                echo 'display: none;';
            } ?>">
                <div class="dropdown">
                    <img src="/ick/media/avatar/_default_avatar.svg" alt="Avatar"
                        style="margin-top: -1px; margin-left: 2px;" id="account" data-bs-toggle="dropdown"
                        class="mx-auto rounded-circle img-end d-block" />
                    <ul class="dropdown-menu dropdown-menu-end mt-2 mb-2"
                        style="background-color: var(--footer-bg-color-2) !important; border: 2px solid var(--footer-bg-color-1); border-radius: 15px !important;">
                        <li style="margin-left: 15px; margin-right: 15px; margin-top: 15px;">
                            <p style="font-size: 1.5em; font-weight: bold; line-height: 1px; margin: 0; padding: 0;">
                                Gość
                            </p><br />
                        </li>
                        <li style="margin-left: 15px; margin-right: 15px;">
                            <a class="dropdown-item" href="/ick/logowanie">
                                <i class="bi bi-key-fill"></i> Zaloguj się
                            </a>
                        </li>
                        <li style="margin-left: 15px; margin-right: 15px;">
                            <a class="dropdown-item" href="/ick/rejestracja">
                                <i class="bi bi-person-fill-add"></i> Zarejestruj się
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </nav>

</header>