<?php
session_start();

require_once("db/db_connect.php");

$value_select = $_POST["value_select"];
$value_name = $_POST["value_name"];
$value_surname = $_POST["value_surname"];
$value_email = $_POST["value_email"];
$value_password = $_POST["value_password"];

if (($value_select == "Выберите...") or ($value_name == "") or ($value_surname == "") or ($value_email == "") or ($value_password == "")) {
    header('Location: index.php?accuracy'); // проверка на заполняемость полей
} else { //если все поля заполнены, проверяем нет ли уже ползователя с такой почтой

    if ($value_select == 'Таксист') {
        $email = mysqli_query($lnk, "SELECT email FROM driver");
        $items = array();
        while ($row = mysqli_fetch_assoc($email)) {
            array_push($items, $row);
        }

        $flag = false;
        foreach ($items as $item) {
            if ($item["email"] == $value_email) $flag = true;
        }

        if ($flag) header('Location: index.php?person');
        else { // если такого пользователя еще нет, проверяем пароль на надежность
            $str = "!@#$%^&*()_+=-~`?/.><{}[]|№;";
            $flag = false;
            for ($i = 0; $i < strlen($str); $i++) {
                if (strpos($value_password, $str[$i])) $flag = true;
            }

            if (!$flag) header('Location: index.php?password_error');
            else { // если пароль надежный, регистрируем пользователя и переходим в лк


                $hash_password = password_hash($value_password, PASSWORD_DEFAULT);



                mysqli_query($lnk, "INSERT INTO driver (name, surname, email, password, free) VALUES
                ('$value_name', '$value_surname', '$value_email', '$hash_password', '0')");

                $session_id = session_id();
                if ((!isset($_SESSION['driver'])) or (isset($_SESSION['driver']) and !in_array(session_id(), $_SESSION['driver']))) {
                    $_SESSION['driver'][] = session_id();

                    mysqli_query($lnk, "UPDATE driver SET session_id = '$session_id' WHERE email = '$value_email'");
                } else {
                    mysqli_query($lnk, "UPDATE driver SET session_id = '' WHERE session_id = '$session_id'");
                    mysqli_query($lnk, "UPDATE driver SET session_id = '$session_id' WHERE email = '$value_email'");
                }

                header('Location: sign.php');
            }
        }
    } else {
        $email = mysqli_query($lnk, "SELECT email FROM users");
        $items = array();
        while ($row = mysqli_fetch_assoc($email)) {
            array_push($items, $row);
        }

        $flag = false;
        foreach ($items as $item) {
            if ($item["email"] == $value_email) $flag = true;
        }

        if ($flag) header('Location: index.php?person');
        else { // если такого пользователя еще нет, проверяем пароль на надежность
            $str = "!@#$%^&*()_+=-~`?/.><{}[]|№;";
            $flag = false;
            for ($i = 0; $i < strlen($str); $i++) {
                if (strpos($value_password, $str[$i])) $flag = true;
            }

            if (!$flag) header('Location: index.php?password_error');
            else { // если пароль надежный, регистрируем пользователя и переходим в лк


                $hash_password = password_hash($value_password, PASSWORD_DEFAULT);
                mysqli_query($lnk, "INSERT INTO users (name, surname, email, password) VALUES
                ('$value_name', '$value_surname', '$value_email', '$hash_password')");


                $session_id = session_id();
                if ((!isset($_SESSION['user'])) or (isset($_SESSION['user']) and !in_array(session_id(), $_SESSION['user']))) {
                    $_SESSION['user'][] = session_id();

                    mysqli_query($lnk, "UPDATE users SET session_id = '$session_id' WHERE email = '$value_email'");
                } else {
                    mysqli_query($lnk, "UPDATE users SET session_id = '' WHERE session_id = '$session_id'");
                    mysqli_query($lnk, "UPDATE users SET session_id = '$session_id' WHERE email = '$value_email'");
                }

                header('Location: sign.php');
            }
        }
    }
}
