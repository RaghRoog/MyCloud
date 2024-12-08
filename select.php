<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szymon Pomieciński</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
        session_start();

        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            header("Location: ../logowanie/index.html");
            exit();
        }

        
    ?>
    <div class="container mt-5">
        <h2>Wybierz plik do przesłania</h2>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="fileToUpload" class="form-label">Wybierz plik:</label>
                <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Prześlij plik</button>
            <a href="userDir.php" class="btn btn-secondary">Powrót</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
