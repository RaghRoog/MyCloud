<?php declare(strict_types=1);  /* Ta linia musi być pierwsza */ ?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
	<meta name="description" content="Twój Opis">
	<meta name="author" content="Twoje dane">
	<meta name="keywords" content="Twoje słowa kluczowe">
	<title>Szymon Pomieciński</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
	<style type="text/css" class="init"></style>
	<link rel="stylesheet" type="text/css" href="twoj_css.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
	<script type="text/javascript" src="twoj_js.js"></script> 
</head>

<body>
	<div id='myHeader'> </div>	
	<main> 
		<section class="sekcja1">	
			<div class="container-fluid">
                <a href="userDir.php" class="btn btn-secondary">
                    <i class="fas fa-level-up-alt"></i> Wyjdź
                </a>
                <a href="select.php" class="btn btn-primary">
                    <i class="fas fa-cloud-upload-alt"></i> Prześlij plik
                </a>

                <?php
                    session_start();
                    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
                        header("Location: ../logowanie/index.html");
                        exit();
                    }

                    $current_dir = isset($_GET['dir']) ? $_GET['dir'] : null;
                    $_SESSION['current_dir'] = $current_dir;

                    if (!is_dir($current_dir)) {
                        echo "Błąd: Katalog użytkownika nie istnieje.";
                        exit();
                    }

                    $files = scandir($current_dir);

                    $files = array_diff($files, array('.', '..'));

                    if (empty($files)) {
                        echo "<h2>Twój katalog jest pusty. Możesz dodać pliki.</h2>";
                    } else {
                        echo "<h2>Zawartość Twojego katalogu:</h2>";
                        echo "<ul>";
                        foreach ($files as $file) {
                            if (is_dir($current_dir . '/' . $file)) {
                                echo "<li><strong>Folder:</strong> <a href='#'>$file</a></li>";
                            }
                            else {
                                echo "<li>
                                        Plik: $file
                                        <a href='usun.php?path=" . $current_dir . "/" . $file . "' onclick='return confirm(\"Czy na pewno chcesz usunąć?\")'>
                                            <i class='fas fa-trash-alt'></i>
                                        </a>
                                    </li>";
                            }
                        }
                        echo "</ul>";
                    }
                ?>
			</div>	
		</section>
	</main>	
</body>
</html>






