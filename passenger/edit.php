<?php
session_start();

if ((!isset($_SESSION['user'])) or (isset($_SESSION['user']) and !in_array(session_id(), $_SESSION['user']))) {
    header('Location: ../index.php');
    die();
}
require_once("../db/db_connect.php");


$session_id = $_POST['session_id'];

$name = $_POST['name'];
$surname = $_POST['surname'];
$email = $_POST['email'];

$emails = mysqli_query($lnk, "SELECT email FROM users WHERE session_id != '$session_id'");
$items = array();
while ($row = mysqli_fetch_assoc($emails)) {
    array_push($items, $row);
}



$flag = false;
foreach ($items as $item) {
    if ($email == $item["email"]) $flag = true;
}

if ($flag) echo 'Пользователь с такой почтой уже зарегистрирован';
else mysqli_query($lnk, "UPDATE users SET name = (\"$name\"), surname = (\"$surname\"), email = (\"$email\") WHERE session_id = '$session_id'");
