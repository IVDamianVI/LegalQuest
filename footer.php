<?php

declare(strict_types=1);
?>

<head>
    <link rel="stylesheet" href="css/style-footer.css">
</head>
<footer class="text-center text-white <?php echo $isMobile ? '' : 'fixed-bottom'; ?>"
    style="background-color: var(--footer-bg-color-1);">
    <!-- Grid container -->
    <div class="container p-2">
        <!-- Section: Social media -->
        <section class="" style="color: var(--footer-color);">
            <!-- Facebook -->
            <a class="btn btn-outline-light btn-floating m-1 rounded-circle social-media"
                href="https://www.facebook.com/" role="button">
                <i class="bi bi-facebook"></i></a>
            <!-- Instagram -->
            <a class="btn btn-outline-light btn-floating m-1 rounded-circle social-media"
                href="https://www.instagram.com/" role="button">
                <i class="bi bi-instagram"></i></a>
            <!-- Github -->
            <a class="btn btn-outline-light btn-floating m-1 rounded-circle social-media" href="https://github.com/"
                role="button">
                <i class="bi bi-github"></i></a>
            <!-- Discord -->
            <a class="btn btn-outline-light btn-floating m-1 rounded-circle social-media" href="https://discordapp.com/"
                role="button">
                <i class="bi bi-discord"></i></a>
        </section>
        <!-- Section: Social media -->
    </div>
    <!-- Grid container -->
    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: var(--footer-bg-color-2); color: var(--footer-color);">
        <!-- Change the year to the current year -->
        <?php echo '© ' . date('Y') . ' '; ?>
        <a class="" style="text-decoration: none; color: var(--primary-color);" href="">Damian Grubecki</a>
        &
        <a class="" style="text-decoration: none; color: var(--primary-color);" href="">Maciej Ludwiczak</a>
    </div>
    <!-- Copyright -->
</footer>