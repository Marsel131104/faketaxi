<?php

$lnk = @mysqli_connect("localhost", "root", "", "demo2") or die('Cannot connect to server');
mysqli_select_db($lnk, "demo2") or die('Cannot open database');
mysqli_set_charset($lnk, 'utf8');

$id = $_GET["id"];
$old_password = $_POST["old_password"];
$new_password = $_POST["new_password"];

if (($old_password == "") or ($new_password == "")) // проверяем чтобы оба поля были заполнены
    header("Location: office.php?id=" . $id . "&accuracy");
else {
    $result = mysqli_query($lnk, "SELECT password FROM users WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    $password = $row["password"];
    // проверяем чтобы старый пароль был введен верно
    if (!password_verify($old_password, $password)) header("Location: office.php?id=" . $id . "&password_error");
    else {
        //проверяем новый пароль на сложность
        $str = "!@#$%^&*()_+=-~`?/.><{}[]|№;";
        $flag = false;
        for ($i = 0; $i < strlen($str); $i++) {
            if (strpos($new_password, $str[$i])) $flag = true;
        }
        if (!$flag) header("Location: office.php?id=" . $id . "&add_symbol");


        else {
            // проверяем чтобы новый пароль отличался от старого
            if (password_verify($new_password, $password)) header("Location: office.php?id=" . $id . "&password_isset");
            else {
                $hash_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                mysqli_query($lnk, "UPDATE users SET password = '" . $hash_new_password . "' WHERE id = $id");
                header("Location: office.php?id=" . $id . "&change_password=succesful");
            }
        }
    }
}
