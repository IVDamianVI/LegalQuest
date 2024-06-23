<?php
require ('access.php');
?>
<style>
    @import url('/ick/css/colors.css');

    .hidden {
        display: none !important;
    }

    .loading-screen {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #1c1c1c;
        color: #ffffff;
        z-index: 9999;
        transition: opacity 0.5s ease;
    }

    .loading-screen img {
        border-radius: 10%;
        -webkit-filter: drop-shadow(0 0 10px 5px rgba(0, 0, 0, 0.5)) !important;
        filter: drop-shadow(0 0 10px 5px rgba(0, 0, 0, 0.5)) !important;
        margin-bottom: 20px;
    }

    .loading-screen.fade-out {
        opacity: 0;
    }

    .spinner {
        border: 8px solid white;
        border-top: 8px solid var(--primary-color);
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
        margin-bottom: 20px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<div id="loading-screen" class="loading-screen">
    <img src="<? echo $logoSRC; ?>" alt="Logo" width="200" height="200">
    <div class="spinner"></div>
    <div>Zrelaksuj się, strona zaraz się pojawi...</div>
</div>

<script>
    window.addEventListener('load', function () {
        document.body.style.overflow = 'hidden';
        const randomTime = Math.random() * (1.5 - 0.5) + 0.5;
        setTimeout(function () {
            const loadingScreen = document.getElementById('loading-screen');
            loadingScreen.classList.add('fade-out');
            setTimeout(function () {
                loadingScreen.classList.add('hidden');
                document.getElementById('navbar').classList.remove('hidden');
                document.body.style.overflow = 'auto';
            }, 100);
        }, randomTime * 1000);
    });
</script>