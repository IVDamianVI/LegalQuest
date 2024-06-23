<?php

declare(strict_types=1);
session_start();
require ('access.php');
$_SESSION['page'] = basename($_SERVER['PHP_SELF']);
$isFooterFixedBottom = true;
?>
<!DOCTYPE html>
<html lang="pl" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Damian Grubecki, Maciej Ludwiczak">
    <meta name="description"
        content="Aplikacja webowa do nauki prawa poprzez rozwiązywanie testów i quizów, stworzona przez Damiana Grubeckiego i Macieja Ludwiczaka. Umożliwia interaktywną naukę i powtórkę materiału przed egzaminami.">
    <meta name="keywords"
        content="<?php echo $appName; ?>, nauka prawa, testy prawnicze, quizy prawnicze, interaktywna nauka, egzaminy prawnicze, aplikacja webowa">
    <meta property="og:title" content="<?php echo $appName; ?> - Aplikacja do nauki prawa">
    <meta property="og:description"
        content="Aplikacja webowa do nauki prawa poprzez rozwiązywanie testów i quizów. Ułatwia przyswajanie wiedzy prawnej w interaktywny sposób.">
    <meta property="og:image" content="https://ivdamianvi.smallhost.pl/ick/media/logo/logo-og.png">
    <meta property="og:url" content="https://ivdamianvi.smallhost.pl/ick/">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $appName; ?> - Aplikacja do nauki prawa">
    <meta name="twitter:description"
        content="Aplikacja webowa do nauki prawa poprzez rozwiązywanie testów i quizów. Ułatwia przyswajanie wiedzy prawnej w interaktywny sposób.">
    <meta name="twitter:image" content="https://ivdamianvi.smallhost.pl/ick/media/logo/logo-og.png">
    <link rel="icon" href="<?php echo $faviconSRC; ?>">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/style-page-main.css">
    <link rel="stylesheet" href="css/lightModeColors.css">
    <link rel="stylesheet" href="css/darkModeColors.css">
    <script src="script/loadHeader.js"></script>
    <script src="//geoip-js.com/js/apis/geoip2/v2.1/geoip2.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript" language="javascript"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js" type="text/javascript"
        language="javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js" type="text/javascript"
        language="javascript"></script>
    <title>O projekcie <?php echo $appName; ?></title>
</head>

<body onload="autoSubmitForm()">
    <?php include "header.php"; ?>
    <?php include "loading.php"; ?>
    <main class="container">
        <h1>O projekcie</h1>
        <p>Projekt aplikacji webowej przeznaczonej do nauki prawa poprzez rozwiązywanie testów i quizów został stworzony
            przez Damiana Grubeckiego i Macieja Ludwiczaka.</p>
        <p>Celem projektu jest ułatwienie użytkownikom przyswajania wiedzy prawnej w interaktywny sposób oraz
            zwiększenie zaangażowania w proces nauki. Aplikacja ma również dostarczać narzędzie umożliwiające efektywną
            powtórkę przed egzaminami.</p>
        <br />
        <h2>Funkcje aplikacji</h2>
        <p>Aplikacja oferuje możliwość rozwiązywania testów z różnych kategorii prawa, takich jak:</p>
        <ul>
            <li>Konstytucja</li>
            <li>Prawo cywilne
                <ul>
                    <li>Kodeks cywilny</li>
                    <li>Kodeks postępowania cywilnego</li>
                </ul>
            </li>
            <li>Prawo karne
                <ul>
                    <li>Kodeks karny</li>
                    <li>Kodeks postępowania karnego</li>
                    <li>Kodeks wykroczeń</li>
                </ul>
            </li>
            <li>Prawo administracyjne
                <ul>
                    <li>Kodeks postępowania administracyjnego</li>
                </ul>
            </li>
            <li>Prawo spółek handlowych</li>
            <li>Prawo o ustroju sądów powszechnych</li>
            <li>Prawo międzynarodowe</li>
            <li>Prawo unijne</li>
        </ul>
        <p>Użytkownicy mogą zdobywać punkty, odznaki i osiągnięcia, a także powtarzać pytania, na które udzielili
            błędnych odpowiedzi, co pozwala na skuteczniejsze przyswajanie wiedzy.</p>
        <br />
        <h2>Docelowa grupa użytkowników</h2>
        <p>Aplikacja jest skierowana do wszystkich, którzy chcą poszerzyć swoją wiedzę prawniczą, w tym studentów prawa
            oraz osoby przygotowujące się do egzaminów radcowskich, adwokackich i innych związanych z prawem.</p>
        <br />
        <h2>Rozwój aplikacji</h2>
        <p>Aplikacja jest stale rozwijana, aby jak najlepiej odpowiadać na potrzeby użytkowników i dostarczać im
            narzędzi do efektywnej nauki prawa. Planowane są nowe funkcje i rozszerzenia, które zwiększą jej
            funkcjonalność i użyteczność.</p>
        <br />
        <h2>Korzyści z korzystania z aplikacji</h2>
        <p>Nasza aplikacja oferuje liczne korzyści, w tym:</p>
        <ul>
            <li>Interaktywne testy dostosowane do wymogów egzaminacyjnych, co pozwala na skuteczniejszą naukę.</li>
            <li>Możliwość zdobywania punktów i osiągnięć, co zwiększa motywację do regularnego korzystania z aplikacji.
            </li>
            <li>System powtarzania błędnych odpowiedzi, który umożliwia efektywną powtórkę materiału.</li>
        </ul>
        <br />
        <h2>Podziękowania</h2>
        <p>Chcielibyśmy wyrazić nasze głębokie podziękowania dla osób, które przyczyniły się do powstania tego projektu:
        </p>
        <p><strong>Olaf Kuszewicz</strong> – za jego niezwykły talent i zaangażowanie w tworzenie ikon kategorii. Jego
            wkład graficzny znacząco wpłynął na estetykę i użyteczność naszej aplikacji.</p>
        <p><strong>Sebastian Zmudziński</strong> – za nieocenione wsparcie merytoryczne oraz wkład w dodawanie treści na
            stronie. Jego wiedza i doświadczenie były kluczowe dla zapewnienia wysokiej jakości i wartości edukacyjnej
            zawartości aplikacji.</p>
        <br />
        <p>Projekt został zrealizowany w ramach studiowania kierunku Informatyka Stosowana na <a
                href="https://pbs.edu.pl/pl/" class="link">Politechnice Bydgoskiej</a>.
        </p>
    </main>
    <?php require_once 'footer.php'; ?>
    <form method="POST" id="getInfo" name="getInfo">
        <input type="hidden" value="" id="display" name="display" />
        <input type="hidden" value="" id="viewport" name="viewport" />
        <input type="hidden" value="" id="colors" name="colors" />
        <input type="hidden" value="" id="cookies" name="cookies" />
        <input type="hidden" value="" id="java" name="java" />
        <input type="hidden" value="" id="page" name="page" />
        <input type="hidden" value="" id="city" name="city" />
        <input type="hidden" value="" id="coords" name="coords" />
    </form>
    <script type="text/javascript" src="script/getInfo.js"></script>
    <script>
        function autoSubmitForm() {
            var formData = new FormData(document.getElementById("getInfo"));
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "controller/get-user-info-controller.php", true);
            xhr.onload = function () {
                if (xhr.status === 200) { }
            };
            xhr.send(formData);
        }
    </script>
</body>

</html>