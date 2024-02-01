<?php
include("../db/db_connect.php");


$id = $_POST['id'];
$name = $_POST['name'];
$surname = $_POST['surname'];
$email = $_POST['email'];

$emails = mysqli_query($lnk, "SELECT email FROM users WHERE id != $id");
$items = array();
while ($row = mysqli_fetch_assoc($emails)) {
    array_push($items, $row);
}



$flag = false;
foreach ($items as $item) {
    if ($email == $item["email"]) $flag = true;
}

if ($flag) echo 'Пользователь с такой почтой уже зарегистрирован';
else mysqli_query($lnk, "UPDATE users SET name = (\"$name\"), surname = (\"$surname\"), email = (\"$email\") WHERE id = $id");
