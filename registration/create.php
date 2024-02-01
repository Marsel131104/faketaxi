<?php
session_start();


$lnk = @mysqli_connect("localhost", "root", "", "course") or die('Cannot connect to server');
mysqli_select_db($lnk, "course") or die('Cannot open database');
mysqli_set_charset($lnk, 'utf8');

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
                $_SESSION["login"] = $value_email;


                $hash_password = password_hash($value_password, PASSWORD_DEFAULT);
                mysqli_query($lnk, "INSERT INTO driver (name, surname, email, password, free) VALUES
                ('$value_name', '$value_surname', '$value_email', '$hash_password', '0')");

                $result = mysqli_query($lnk, "SELECT id FROM driver WHERE email = '" . $value_email . "'");
                $row = mysqli_fetch_assoc($result);
                $id = $row["id"];
                header('Location: /taxi/driver/main.php?id=' . $id . '');
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
                $_SESSION["login"] = $value_email;


                $hash_password = password_hash($value_password, PASSWORD_DEFAULT);
                mysqli_query($lnk, "INSERT INTO users (name, surname, email, password) VALUES
                ('$value_name', '$value_surname', '$value_email', '$hash_password')");

                $result = mysqli_query($lnk, "SELECT id FROM users WHERE email = '$value_email'");
                $row = mysqli_fetch_assoc($result);
                $id = $row["id"];
                header('Location: /taxi/passenger/main.php?id=' . $id . '');
            }
        }
    }
}
