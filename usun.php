<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../logowanie/index.html");
    exit();
}

if (isset($_GET['path'])) {
    $path = $_GET['path'];

    //sprawdzenie czy plik lub folder istnieje
    if (!file_exists($path)) {
        echo "Błąd: Taki plik lub folder nie istnieje.";
        exit();
    }

    //funkcja do usuwania folderow z plikami
    function deleteDirectory($dir) {
        if (is_dir($dir)) {
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) {
                $filePath = $dir . '/' . $file;
                if (is_dir($filePath)) {
                    deleteDirectory($filePath);
                } else {
                    unlink($filePath);
                }
            }
            rmdir($dir);
        }
    }

    //sprawdzanie czy to plik czy folder
    if (is_file($path)) {
        if (unlink($path)) {
            echo "Plik został usunięty.";
        } else {
            echo "Błąd podczas usuwania pliku.";
        }
    } elseif (is_dir($path)) {
        deleteDirectory($path);
        echo "Folder został usunięty.";
    } else {
        echo "Nieprawidłowa ścieżka.";
    }
    header("Location: userDir.php");
    exit();
} else {
    echo "Brak ścieżki do pliku lub folderu.";
}
?>
