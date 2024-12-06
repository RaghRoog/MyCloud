<?php
session_start();

$target_dir = $_SESSION['current_dir'];
$target_file = $target_dir . "/". basename($_FILES["fileToUpload"]["name"]);
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){ 
    //$_SESSION['current_dir'] = $_SESSION['user_dir'];
    header("location: ./userDir.php");
}
else { echo "Błąd podczas przesyłania pliku."; }

?>

