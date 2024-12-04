<?php
session_start();
$user = htmlentities($_POST['user'], ENT_QUOTES, "UTF-8");
$pass = htmlentities($_POST['pass'], ENT_QUOTES, "UTF-8");
$screen_resolution = $_POST['screenResolution'] ?? 'Brak danych';
$window_resolution = $_POST['windowResolution'] ?? 'Brak danych';
$colors_quantity = $_POST['colorsQuantity'] ?? 'Brak danych';
$cookies_enabled = $_POST['cookiesEnabled'] ?? 'Brak danych';
$java_enabled = $_POST['javaEnabled'] ?? 'Brak danych';
$browser_language = $_POST['browserLanguage'] ?? 'Brak danych';
$browser_details = get_browser(null, true);
$browser_name = $browser_details['browser'] ?? 'Nieznana';
$link = mysqli_connect("mysql02.raghroog.beep.pl", "raghroog4", "bazaraghroog4", "z4_raghroog");
if (!$link) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}
mysqli_query($link, "SET NAMES 'utf8'");
$result = mysqli_query($link, "SELECT * FROM users WHERE username='$user'");
$rekord = mysqli_fetch_array($result);
if (!$rekord) {
    mysqli_close($link);
    echo "Brak użytkownika o takim loginie!";
} else {
    if ($rekord['password'] == $pass) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $insert = mysqli_query($link, "INSERT INTO goscieportalu 
            (ipaddress, browser_name, screen_resolution, window_resolution, colors_quantity, cookies_enabled, java_enabled, browser_language) 
            VALUES 
            ('$ipaddress', '$browser_name', '$screen_resolution', '$window_resolution', '$colors_quantity', '$cookies_enabled', '$java_enabled', '$browser_language')");

        if (!$insert) {
            echo "Błąd podczas zapisywania danych gościa: " . mysqli_error($link);
            mysqli_close($link);
            exit();
        }
        $_SESSION['loggedin'] = true;
        $_SESSION['avatar'] = $rekord['avatar'];
        $_SESSION['user_id'] = $rekord['id'];
        $_SESSION['username'] = $rekord['username'];
        mysqli_close($link);
        header("location: ../index.php");
        exit();
    } else {
        mysqli_close($link);
        echo "Błąd w haśle!";
    }
}
?>
