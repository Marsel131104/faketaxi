<?php
session_start();
require_once("db/db_connect.php");


$value_select = $_POST["value_select"];
$value_email = $_POST["value_email"];
$value_password = $_POST["value_password"];

if (($value_select == "Выберите...") or ($value_email == "") or ($value_password == ""))
    header('Location: sign.php?accuracy'); // проверка на заполняемость полей
else { // проверяем есть ли такой пользователь в бд

    if ($value_select == 'Таксист') {
        $email = mysqli_query($lnk, "SELECT id, email, password FROM driver");
        $items = array();
        while ($row = mysqli_fetch_assoc($email)) {
            array_push($items, $row);
        }

        $flag = false;
        foreach ($items as $item) {
            if ($value_email == $item["email"]) $flag = true;
        }
        if (!$flag) header('Location: sign.php?person');
        else { // проверка на правильность пароля
            $flag = false;
            foreach ($items as $item) {
                if (($value_email == $item["email"]) and (password_verify($value_password, $item["password"]))) {
                    $id = $item["id"]; // если пароль верный, берем id пользователя для дальнейшего вывода
                    $flag = true;      // информации о нем
                }
            }


            if (!$flag) header('Location: sign.php?password_error');
            else {
                $session_id = session_id();
                if ((!isset($_SESSION['driver'])) or (isset($_SESSION['driver']) and !in_array(session_id(), $_SESSION['driver']))) {
                    $_SESSION['driver'][] = session_id();

                    mysqli_query($lnk, "UPDATE driver SET session_id = '$session_id' WHERE email = '$value_email'");
                } else {
                    mysqli_query($lnk, "UPDATE driver SET session_id = '' WHERE session_id = '$session_id'");
                    mysqli_query($lnk, "UPDATE driver SET session_id = '$session_id' WHERE email = '$value_email'");
                }


                header("Location: driver/main.php");
            }
        }
    } else {

        $email = mysqli_query($lnk, "SELECT id, email, password FROM users");
        $items = array();
        while ($row = mysqli_fetch_assoc($email)) {
            array_push($items, $row);
        }

        $flag = false;
        foreach ($items as $item) {
            if ($value_email == $item["email"]) $flag = true;
        }
        if (!$flag) header('Location: sign.php?person');
        else { // проверка на правильность пароля
            $flag = false;
            foreach ($items as $item) {
                if (($value_email == $item["email"]) and (password_verify($value_password, $item["password"]))) {
                    $id = $item["id"]; // если пароль верный, берем id пользователя для дальнейшего вывода
                    $flag = true;      // информации о нем
                }
            }


            if (!$flag) header('Location: sign.php?password_error');
            else {

                $session_id = session_id();
                if ((!isset($_SESSION['user'])) or (isset($_SESSION['user']) and !in_array(session_id(), $_SESSION['user']))) {
                    $_SESSION['user'][] = session_id();

                    mysqli_query($lnk, "UPDATE users SET session_id = '$session_id' WHERE email = '$value_email'");
                } else {
                    $result = mysqli_query($lnk, "SELECT new_order, session_id FROM users WHERE email = '$value_email'");
                    $row = mysqli_fetch_assoc($result);

                    $old_session_id = $row['session_id'];


                    if (!empty($row['new_order'])) {
                        mysqli_query($lnk, "UPDATE driver SET id_user = '$session_id' WHERE id_user = '$old_session_id'");
                        mysqli_query($lnk, "UPDATE users SET session_id = '$session_id' WHERE email = '$value_email'");

                    } else {
                        mysqli_query($lnk, "UPDATE users SET session_id = '' WHERE session_id = '$session_id'");
                        mysqli_query($lnk, "UPDATE users SET session_id = '$session_id' WHERE email = '$value_email'");
                    }


                }

                header("Location: passenger/main.php");
            }
        }
    }
}
