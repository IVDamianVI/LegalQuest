<?php

declare(strict_types=1);
?>

<footer class="text-center text-white <?php if (!isset($isFooterFixedBottom))
    echo 'fixed-bottom'; ?>" id="footer" style="background-color: var(--footer-bg-color-1);">
    <div class="container p-2">
        <section class="" style="color: var(--footer-color);">
            <a class="btn btn-outline-light btn-floating m-1 rounded-circle social-media"
                href="https://www.facebook.com/" role="button">
                <i class="bi bi-facebook"></i></a>
            <a class="btn btn-outline-light btn-floating m-1 rounded-circle social-media"
                href="https://www.instagram.com/" role="button">
                <i class="bi bi-instagram"></i></a>
            <a class="btn btn-outline-light btn-floating m-1 rounded-circle social-media" href="https://github.com/"
                role="button">
                <i class="bi bi-github"></i></a>
            <a class="btn btn-outline-light btn-floating m-1 rounded-circle social-media" href="https://discordapp.com/"
                role="button">
                <i class="bi bi-discord"></i></a>
        </section>
    </div>
    <div class="text-center p-3" style="background-color: var(--footer-bg-color-2); color: var(--footer-color);">
        <?php echo $appName; ?>
        <?php echo '© ' . date('Y') . ' '; ?>
        <br />
        <a class="" style="text-decoration: none; color: var(--primary-color);" href="">Damian Grubecki</a>
        &
        <a class="" style="text-decoration: none; color: var(--primary-color);" href="">Maciej Ludwiczak</a>
    </div>
</footer>
<script>
    if (/Mobi/.test(navigator.userAgent)) {
        document.getElementById('footer').classList.remove('fixed-bottom');
    }
</script>