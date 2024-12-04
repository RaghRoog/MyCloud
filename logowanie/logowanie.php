<?php
session_start();
$user = htmlentities($_POST['user'], ENT_QUOTES, "UTF-8");
$pass = htmlentities($_POST['pass'], ENT_QUOTES, "UTF-8");

$link = mysqli_connect("mysql02.raghroog.beep.pl", "raghroog5", "bazaraghroog5", "z5_raghroog");
if (!$link) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}
mysqli_query($link, "SET NAMES 'utf8'");

//sprawdzenie uzytkownika w tabeli users
$result = mysqli_query($link, "SELECT * FROM users WHERE username='$user'");
$rekord = mysqli_fetch_array($result);

if (!$rekord) {
    echo "Brak użytkownika o takim loginie!";
    mysqli_close($link);
    exit();
}

//sprawdzenie czy konto jest zablokowane
$last_failed_attempts = mysqli_query($link, "
    SELECT datetime 
    FROM goscieportalu 
    WHERE username='$user' AND success='0' 
    ORDER BY datetime DESC 
    LIMIT 3
");
$failed_attempts = [];
while ($row = mysqli_fetch_assoc($last_failed_attempts)) {
    $failed_attempts[] = strtotime($row['datetime']);
}

if (count($failed_attempts) == 3) {
    $first_failed_time = $failed_attempts[2];
    if (time() - $first_failed_time < 60) {
        echo "Konto zablokowane na 1 minutę z powodu zbyt wielu błędnych logowań.";
        mysqli_query($link, "
            INSERT INTO break_ins (username, ipaddress) 
            VALUES ('$user', '{$_SERVER['REMOTE_ADDR']}')
        ");
        mysqli_close($link);
        exit();
    }
}

//weryfikacja hasla
if ($rekord['password'] == $pass) {
    //logowanie udane
    $base_dir = '../user_dirs/';
    $user_dir = $base_dir . $user;

    if (!is_dir($user_dir)) {
        echo "Katalog użytkownika nie istnieje!";
        mysqli_close($link);
        exit();
    }
    mysqli_query($link, "
        INSERT INTO goscieportalu (username, ipaddress, success) 
        VALUES ('$user', '{$_SERVER['REMOTE_ADDR']}', 1)
    ");

    //sprawdzenie ostatniej nieudanej proby logowania
    $last_failed = mysqli_query($link, "
        SELECT datetime, ipaddress 
        FROM goscieportalu 
        WHERE username='$user' AND success='0' 
        ORDER BY datetime DESC 
        LIMIT 1
    ");
    $last_failed_data = mysqli_fetch_assoc($last_failed);
    if ($last_failed_data) {
        $last_failed_time = $last_failed_data['datetime'];
        $last_failed_ip = $last_failed_data['ipaddress'];
        echo "<p style='color: red;'>Ostatnia nieudana próba logowania: $last_failed_time z IP: $last_failed_ip</p>";
    }

    //ustawienie sesji
    $_SESSION['loggedin'] = true;
    $_SESSION['avatar'] = $rekord['avatar'];
    $_SESSION['user_id'] = $rekord['id'];
    $_SESSION['username'] = $rekord['username'];
    $_SESSION['user_dir'] = $user_dir;

    mysqli_close($link);
    header("location: ../index.php");
    exit();
} else {
    //logowanie nieudane
    mysqli_query($link, "
        INSERT INTO goscieportalu (username, ipaddress, success) 
        VALUES ('$user', '{$_SERVER['REMOTE_ADDR']}', 0)
    ");
    echo "Błędne hasło!";
    mysqli_close($link);
    exit();
}
?>
