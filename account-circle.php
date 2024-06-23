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
<div class="dropdown">
    <img src="/ick/media/avatar/<?php echo $avatar; ?>" alt="Avatar" style="margin-top: -1px; margin-left: 2px;"
        id="account" data-bs-toggle="dropdown" class="mx-auto rounded-circle img-end d-block" />
    <ul class="dropdown-menu dropdown-menu-end mt-2 mb-2"
        style="background-color: var(--footer-bg-color-2) !important; border: 2px solid var(--footer-bg-color-1); border-radius: 15px !important;">
        <li style="margin-left: 15px; margin-right: 15px; margin-top: 15px;">
            <p style="font-size: 1.5em; font-weight: bold; line-height: 1px; margin: 0; padding: 0;">
                <?php echo $user; ?>
            </p><br>
            <a href="profil/<?php echo $_SESSION['user_id']; ?>" id="showProfile" class="text-top"
                style="line-height: 0px; margin: 0; padding: 0;">
                <p>Zobacz profil</p>
            </a>
        </li>
        <li style="margin-left: 15px; margin-right: 15px;" class="text-center">
            <a class="dropdown-item" href="profil#achievements">
                <i class="bi bi-trophy-fill"></i> Osiągnięcia</a>
        </li>
        <li style="margin-left: 15px; margin-right: 15px;" class="text-center">
            <a class="dropdown-item" href="zmien-avatar">
                <i class="bi bi-image"></i> Zmień avatar</a>
        </li>
        <li style="margin-left: 15px; margin-right: 15px;" class="text-center">
            <a class="dropdown-item" href="controller/logout-controller.php">
                <i class="bi bi-box-arrow-right"></i> Wyloguj się</a>
        </li>
    </ul>
</div>