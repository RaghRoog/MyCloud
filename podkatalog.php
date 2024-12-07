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
    <style>
        .btn-group .btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
</head>

<body>
	<div id='myHeader'> </div>	
	<main> 
		<section class="sekcja1">	
			<div class="container-fluid">
                <div style='display: flex; gap: 10px;'>
                        <a href="userDir.php" class="btn btn-secondary">
                            <i class="fas fa-level-up-alt"></i> Wyjdź
                        </a>
                        <a href="select.php" class="btn btn-primary">
                            <i class="fas fa-cloud-upload-alt"></i> Prześlij plik
                        </a>
                        <form method="GET" action="">
                            <div class="btn-group" role="group" aria-label="View options">
                                <button type="submit" name="view" value="list" class="btn btn-outline-primary <?php echo (isset($_GET['view']) && $_GET['view'] == 'list') || (!isset($_GET['view']) && $view_mode == 'list') ? 'active' : ''; ?>">
                                    Lista
                                </button>
                                <button type="submit" name="view" value="thumbnails" class="btn btn-outline-primary <?php echo (isset($_GET['view']) && $_GET['view'] == 'thumbnails') ? 'active' : ''; ?>">
                                    Miniatury
                                </button>
                                <button type="submit" name="view" value="details" class="btn btn-outline-primary <?php echo (isset($_GET['view']) && $_GET['view'] == 'details') ? 'active' : ''; ?>">
                                    Szczegóły
                                </button>
                            </div>
                            <?php if (isset($_GET['dir'])): ?>
                                <input type="hidden" name="dir" value="<?php echo htmlspecialchars($_GET['dir']); ?>">
                            <?php endif; ?>
                        </form>


                        <a href="logout.php" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Wyloguj się
                        </a>
                    </div>


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
                        $view_mode = isset($_GET['view']) ? $_GET['view'] : 'list';
                        foreach ($files as $file) {
                            $full_path = realpath($current_dir . '/' . $file);
                            $relative_path = '/' . str_replace($_SERVER['DOCUMENT_ROOT'], '', $full_path);                           
                            $file_size = filesize($full_path);
                            $file_mtime = date("Y-m-d H:i:s", filemtime($full_path));
                            if($view_mode == 'list'){//lista
                                if (!is_dir($full_path)) {
                                    echo "<li style='display: flex; align-items: center; gap: 10px; margin-bottom: 20px;'>
                                            <strong>Plik:</strong>
                                            <a href='$relative_path' download>$file</a>
                                            <a href='usun.php?path=" . $current_dir . "/" . $file . "' onclick='return confirm(\"Czy na pewno chcesz usunąć?\")'>
                                                <i class='fas fa-trash-alt'></i>
                                            </a>
                                          </li>";
                                }
                            }elseif($view_mode == 'thumbnails'){//miniatury
                                if (!is_dir($full_path)) {
                                    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {//obrazy
                                        echo "<li style='display: flex; align-items: center; gap: 10px; margin-bottom: 20px;'>
                                                <strong>Plik:</strong>
                                                <a href='$relative_path' download>$file</a>
                                                <a href='usun.php?path=" . $current_dir . "/" . $file . "' onclick='return confirm(\"Czy na pewno chcesz usunąć?\")'>
                                                    <i class='fas fa-trash-alt'></i>
                                                </a>
                                                <a href='$relative_path' target='_blank'>
                                                    <img src='$relative_path' alt='$file' style='max-width: 100px; max-height: 100px; margin-left: 10px;'>
                                                </a>
                                              </li>";
                                    } 
                                    elseif (preg_match('/\.(mp4|webm|ogg)$/i', $file)) {//wideo
                                        echo "<li style='display: flex; align-items: center; gap: 10px; margin-bottom: 20px;'>
                                                <strong>Plik:</strong>
                                                <a href='$relative_path' download>$file</a>
                                                <a href='usun.php?path=" . $current_dir . "/" . $file . "' onclick='return confirm(\"Czy na pewno chcesz usunąć?\")'>
                                                    <i class='fas fa-trash-alt'></i>
                                                </a>
                                                <video controls style='max-width: 300px; display: block;'>
                                                    <source src='$relative_path' type='video/mp4'>
                                                    Twoja przeglądarka nie obsługuje wideo.
                                                </video>
                                              </li>";
                                    } 
                                    elseif (preg_match('/\.(mp3|wav|ogg)$/i', $file)) {//dzwiek
                                        echo "<li style='display: flex; align-items: center; gap: 10px; margin-bottom: 20px;'>
                                                <strong>Plik:</strong>
                                                <a href='$relative_path' download>$file</a>
                                                <a href='usun.php?path=" . $current_dir . "/" . $file . "' onclick='return confirm(\"Czy na pewno chcesz usunąć?\")'>
                                                    <i class='fas fa-trash-alt'></i>
                                                </a>
                                                <audio controls style='display: block;'>
                                                    <source src='$relative_path' type='audio/mpeg'>
                                                    Twoja przeglądarka nie obsługuje dźwięku.
                                                </audio>
                                              </li>";
                                    } 
                                    else {//reszta
                                        echo "<li style='display: flex; align-items: center; gap: 10px; margin-bottom: 20px;'>
                                                <strong>Plik:</strong>
                                                <a href='$relative_path' download>$file</a>
                                                <a href='usun.php?path=" . $current_dir . "/" . $file . "' onclick='return confirm(\"Czy na pewno chcesz usunąć?\")'>
                                                    <i class='fas fa-trash-alt'></i>
                                                </a>
                                              </li>";
                                    }
                                }
                            }elseif($view_mode == 'details'){//szczegoly
                                if (!is_dir($full_path)) {
                                    echo "<li style='display: flex; align-items: center; gap: 10px; margin-bottom: 20px;'>
                                            <strong>Plik:</strong>
                                            <a href='$relative_path' download>$file</a>
                                            <h6>Rozmiar: $file_size B</h6>
                                            <h6>Data utworzenia: $file_mtime</h6>
                                            <a href='usun.php?path=" . $current_dir . "/" . $file . "' onclick='return confirm(\"Czy na pewno chcesz usunąć?\")'>
                                                <i class='fas fa-trash-alt'></i>
                                            </a>
                                        </li>";
                                }
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






