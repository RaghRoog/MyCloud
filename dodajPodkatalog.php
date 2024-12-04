<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../logowanie/index.html");
    exit();
}

$user_dir = $_SESSION['user_dir'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['folder_name'])) {
    $folder_name = trim($_POST['folder_name']);
    $folder_name = basename($folder_name);

    if (!empty($folder_name)) {
        $new_folder = $user_dir . '/' . $folder_name;

        //sprawdzenie czy folder juz istnieje
        if (!is_dir($new_folder)) {
            //tworzenie folderu
            if (mkdir($new_folder, 0777, true)) {
                echo "<div class='alert alert-success'>Folder '$folder_name' został utworzony.</div>";
            } else {
                echo "<div class='alert alert-danger'>Błąd podczas tworzenia folderu.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Folder o tej nazwie już istnieje.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Proszę podać nazwę folderu.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Twój Opis">
    <meta name="author" content="Twoje dane">
    <meta name="keywords" content="Twoje słowa kluczowe">
    <title>Dodaj folder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Dodaj nowy folder</h2>
        <form method="POST">
            <div class="form-group">
                <label for="folder_name">Nazwa folderu:</label>
                <input type="text" class="form-control" id="folder_name" name="folder_name" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">
                <i class="fas fa-folder-plus"></i> Dodaj folder
            </button>
        </form>
        <br>
        <a href="userDir.php" class="btn btn-secondary">Powrót do katalogu</a>
    </div>
</body>
</html>

