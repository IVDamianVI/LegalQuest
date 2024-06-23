<?php

declare(strict_types=1);
session_start();
require ('../access.php');

$isFooterFixedBottom = true;

if ($_SESSION['loggedin'] == true) {
    if ($_SESSION['userGroup'] == 'admin') {
        $dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
        if (!$dbConn) {
            die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
        }
    } else {
        header('Location: /ick/');
        exit();
    }
} else {
    header('Location: /ick/');
    exit();
}

$dbConn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
if (!$dbConn) {
    die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
}

$categories = mysqli_query($dbConn, "SELECT id, name FROM category");

$subcategories = [];
$result = mysqli_query($dbConn, "SELECT id, name, category_id FROM subcategory");
while ($row = mysqli_fetch_assoc($result)) {
    $subcategories[$row['category_id']][] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];
    $answer_a = $_POST['answer_a'];
    $answer_b = $_POST['answer_b'];
    $answer_c = $_POST['answer_c'];
    $correct_answer = $_POST['correct_answer'];
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];
    $comment = $_POST['comment'];

    $correct_answer_hash = hash('sha256', $correct_answer);

    $stmt = mysqli_prepare($dbConn, "INSERT INTO questions (question, answer_a, answer_b, answer_c, correct_answer_hash, category_id, subcategory_id, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sssssiis', $question, $answer_a, $answer_b, $answer_c, $correct_answer_hash, $category_id, $subcategory_id, $comment);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Pytanie zostało dodane pomyślnie.";
    } else {
        $message = "Błąd podczas dodawania pytania: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($dbConn);
} else {
    $message = "";
}

?>

<!DOCTYPE html>
<html lang="pl" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Damian Grubecki, Maciej Ludwiczak">
    <meta name="description" content="">
    <meta name="keywords" content="admin, add question, quiz">
    <link rel="stylesheet" href="/ick/css/colors.css">
    <link rel="stylesheet" href="/ick/css/style-page-main.css">
    <link rel="stylesheet" href="/ick/css/style-page-kategoria.css">
    <link rel="stylesheet" href="/ick/css/lightModeColors.css">
    <link rel="stylesheet" href="/ick/css/darkModeColors.css">
    <link rel="icon" href="<?php echo $faviconSRC; ?>">
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
    <title>Dodaj pytanie | Admin Panel | <?php echo $appName; ?></title>
    <style>
        main.container {
            max-width: 1000px;
        }
    </style>
</head>

<body>
    <main class="container" style="margin-top: 10px;">
        <a href="panel.php" class="link">
            <p class="text-center">
                <i class="bi bi-arrow-return-left"></i> Wróć na stronę panelu
            </p>
        </a>
        <h1>Dodaj nowe pytanie</h1>
        <br />
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="add-question.php" method="POST">
            <div class="mb-3">
                <label for="question" class="form-label">Pytanie</label>
                <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="answer_a" class="form-label">Poprawna odpowiedź</label>
                <input type="text" class="form-control" id="answer_a" name="answer_a" required>
            </div>
            <div class="mb-3">
                <label for="answer_b" class="form-label">Odpowiedź B</label>
                <input type="text" class="form-control" id="answer_b" name="answer_b" required>
            </div>
            <div class="mb-3">
                <label for="answer_c" class="form-label">Odpowiedź C</label>
                <input type="text" class="form-control" id="answer_c" name="answer_c" required>
            </div>
            <div class="mb-3" hidden>
                <label for="correct_answer" class="form-label">Poprawna odpowiedź</label><br />
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="correct_a" name="correct_answer" value="a" required
                        checked>
                    <label class="form-check-label" for="correct_a">A</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="correct_b" name="correct_answer" value="b"
                        required>
                    <label class="form-check-label" for="correct_b">B</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="correct_c" name="correct_answer" value="c"
                        required>
                    <label class="form-check-label" for="correct_c">C</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategoria pytania</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo $category['id'] . ' | ' . $category['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3" id="subcategory_div">
                <label for="subcategory_id" class="form-label">Podkategoria pytania</label>
                <select class="form-select" id="subcategory_id" name="subcategory_id" required>
                </select>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Komentarz do pytania</label>
                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Dodaj pytanie</button>
            </div>
        </form>
    </main>
    <?php include_once '../footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const subcategories = <?php echo json_encode($subcategories); ?>;
            const categorySelect = document.getElementById('category_id');
            const subcategoryDiv = document.getElementById('subcategory_div');
            const subcategorySelect = document.getElementById('subcategory_id');

            categorySelect.addEventListener('change', function () {
                const categoryId = this.value;
                subcategorySelect.innerHTML = '';

                if (subcategories[categoryId]) {
                    subcategories[categoryId].forEach(function (subcategory) {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;
                        subcategorySelect.appendChild(option);
                    });
                    subcategoryDiv.style.display = 'block';
                } else {
                    subcategoryDiv.style.display = 'none';
                }
            });

            categorySelect.dispatchEvent(new Event('change'));
        });
    </script>

</body>

</html>