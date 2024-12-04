<?php
session_start();
$user = htmlentities($_POST['user'], ENT_QUOTES, "UTF-8");
$email = htmlentities($_POST['email'], ENT_QUOTES, "UTF-8");
$pass = htmlentities($_POST['pass'], ENT_QUOTES, "UTF-8");
$pass_confirm = htmlentities($_POST['rep-pass'], ENT_QUOTES, "UTF-8");

$link = mysqli_connect("mysql02.raghroog.beep.pl", "raghroog5", "bazaraghroog5", "z5_raghroog");
if (!$link) {
    echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
    exit();
}
mysqli_query($link, "SET NAMES 'utf8'");

$result = mysqli_query($link, "SELECT * FROM users WHERE username='$user'");
$rekord = mysqli_fetch_array($result);

if ($rekord) {
    echo "Użytkownik o takim loginie już istnieje!";
    mysqli_close($link);
    exit();
}

if ($pass !== $pass_confirm) {
    echo "Hasła nie są zgodne!";
    mysqli_close($link);
    exit();
}

$default_avatar = '../avatars/default-avatar.png'; 
$avatar_path = $default_avatar;

if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($_FILES['avatar']['type'], $allowed_types)) {
        $upload_dir = '../avatars/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); 
        }
        $avatar_filename = uniqid() . '_' . basename($_FILES['avatar']['name']);
        $avatar_path = $upload_dir . $avatar_filename;
        move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar_path);
        $base_url = 'https://raghroog.beep.pl/MyCloud/avatars/';
        $avatar_url = $base_url . $avatar_filename; 
    } else {
        echo "Nieprawidłowy format pliku. Dozwolone formaty to: JPEG, PNG, GIF.";
        mysqli_close($link);
        exit();
    }
} else {
    $avatar_url = 'https://raghroog.beep.pl/MyCloud/avatars/default-avatar.png'; 
}

$insert = mysqli_query($link, "INSERT INTO users (username, email, password, avatar) VALUES ('$user', '$email', '$pass', '$avatar_url')");
if ($insert) {
    header("Location: ../logowanie/index.html");
    exit();
} else {
    echo "Błąd podczas rejestracji. Spróbuj ponownie.";
}

mysqli_close($link);
?>
