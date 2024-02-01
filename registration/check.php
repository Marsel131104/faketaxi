<?php
// session_start();

$lnk = @mysqli_connect("localhost", "root", "", "course") or die('Cannot connect to server');
mysqli_select_db($lnk, "course") or die('Cannot open database');
mysqli_set_charset($lnk, 'utf8');

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

                header("Location: /taxi/driver/main.php?id=" . $id . "");
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
                header("Location: /taxi/passenger/main.php?id=" . $id . "");
            }
        }
    }
}
