<?php

declare(strict_types=1);
session_start();
?>
<!-- Początek: Kod okrągłego avatara w <header> -->
<div class="dropdown" style="<?php if (!isset ($_SESSION['loggedin'])) {
    echo 'display: none;';
} ?>">
    <img src="media/avatar/<?php echo $_SESSION['avatar']; ?>" alt="Avatar" style="margin-top: -1px; margin-left: 2px;"
        id="account" data-bs-toggle="dropdown" class="mx-auto rounded-circle img-end d-block" />
    <ul class="dropdown-menu dropdown-menu-end mt-2 mb-2"
        style="background-color: #0e0e0e !important; border: 2px solid #151515; border-radius: 15px !important;">
        <li style="margin-left: 15px; margin-right: 15px; margin-top: 15px;">
            <!-- Wyświetlenie nazwy użytkownika -->
            <p style="font-size: 1.5em; font-weight: bold; line-height: 1px; margin: 0; padding: 0;">
                <?php echo $_SESSION['user']; ?>
            </p><br>
            <!-- Przycisk do strony profilu -->
            <a href="profil.php" id="showProfile" class="text-top" style="line-height: 0px; margin: 0; padding: 0;">
                <p>Zobacz profil</p>
            </a>
        </li>
        <!-- Przycisk do chatu -->
        <li style="margin-left: 15px; margin-right: 15px;" class="text-center">
            <a class="dropdown-item" href="chat.php">
                <i class="bi bi-chat-dots-fill"></i> Komunikator</a>
        </li>
        <!-- Przycisk do zmiany avatara -->
        <li style="margin-left: 15px; margin-right: 15px;" class="text-center">
            <a class="dropdown-item" href="zmien-avatar.php">
                <i class="bi bi-image"></i> Zmień avatar</a>
        </li>
        <!-- Przycisk do chatu -->
        <li style="margin-left: 15px; margin-right: 15px;" class="text-center">
            <a class="dropdown-item" href="geolocation.php">
                <i class="bi bi-clock-history"></i> Historia logowań</a>
        </li>
        <!-- Przycisk do wylogowania -->
        <li style="margin-left: 15px; margin-right: 15px;" class="text-center">
            <a class="dropdown-item" href="controller/logout-controller.php">
                <i class="bi bi-box-arrow-right"></i> Wyloguj się</a>
        </li>
    </ul>
</div>
<!-- Koniec: Kod okrągłego avatara w <header> -->