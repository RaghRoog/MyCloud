<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../logowanie/index.html");
    exit();
}

$user_dir = $_SESSION['user_dir'];

//sprawdzenie czy katalog uzytkownika istnieje
if (!is_dir($user_dir)) {
    echo "Błąd: Katalog użytkownika nie istnieje.";
    exit();
}

//pobranie listy plikow i folderow w katalogu
$files = scandir($user_dir);

//usuniecie "." i ".." z listy bo sa to odniesienia do biezacego i nadrzednego katalogu
$files = array_diff($files, array('.', '..'));

//jezeli katalog jest pusty
if (empty($files)) {
    echo "Twój katalog jest pusty. Możesz dodać pliki lub foldery.";
} else {
    echo "<h2>Zawartość Twojego katalogu:</h2>";
    echo "<ul>";
    foreach ($files as $file) {
        //jezeli jest to folder
        if (is_dir($user_dir . '/' . $file)) {
            echo "<li><strong>Folder:</strong> <a href='podkatalog.php?dir=" . $user_dir . "/" . $file . "'>$file</a></li>";
        }
        //jezeli jest to plik
        else {
            echo "<li>Plik: $file</li>";
        }
    }
    echo "</ul>";
}
?>
