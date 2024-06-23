<?php
require 'access.php';

function URL($key = null)
{
    $arr = explode("/", trim($_GET['url'] ?? 'index', "/"));
    if (!is_numeric($key))
        return $arr;

    return $arr[$key] ?? '';
}

$file = URL(0) . '.php';


if (file_exists($file)) {
    if (URL(0) == 'kategoria' && URL(1) != '') {
        $GETkategoria = URL(1);
    }
    if (URL(0) == 'test' && URL(1) != '') {
        $GETQuestion = URL(1);
    }
    if (URL(0) == 'wynik-testu' && URL(1) != '') {
        $GETTestID = URL(1);
    }
    if (URL(0) == 'profil' && URL(1) != '') {
        $GETIDUser = URL(1);
    }
    require $file;
} else {
    header('Location: /ick/');
    exit();
}
?>