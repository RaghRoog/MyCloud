<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../logowanie/index.html");
    exit();
}

$current_dir = isset($_GET['dir']) ? $_GET['dir'] : null;

if (!is_dir($current_dir)) {
    echo "Błąd: Katalog użytkownika nie istnieje.";
    exit();
}

$files = scandir($current_dir);

$files = array_diff($files, array('.', '..'));

if (empty($files)) {
    echo "Twój katalog jest pusty. Możesz dodać pliki lub foldery.";
} else {
    echo "<h2>Zawartość Twojego katalogu:</h2>";
    echo "<ul>";
    foreach ($files as $file) {
        if (is_dir($current_dir . '/' . $file)) {
            echo "<li><strong>Folder:</strong> <a href='#'>$file</a></li>";
        }
        else {
            echo "<li>Plik: $file</li>";
        }
    }
    echo "</ul>";
}
?>
