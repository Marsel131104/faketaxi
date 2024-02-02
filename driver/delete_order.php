<?php

session_start();


if ((!isset($_SESSION['driver'])) or (isset($_SESSION['driver']) and !in_array(session_id(), $_SESSION['driver']))) {
    header('Location: ../index.php');
    die();
}

require_once("../db/db_connect.php");
date_default_timezone_set('Europe/Moscow');

$id_user = $_POST['id_user'];
$cost = $_POST['cost'];
//получаем данные заказа
$result = mysqli_query($lnk, "SELECT new_order, status_order, history FROM driver WHERE id_user = '$id_user'");
$row = mysqli_fetch_assoc($result);

// получаем всю историю заказов пользователя
$result2 = mysqli_query($lnk, "SELECT history FROM users WHERE session_id = '$id_user'");
$row2 = mysqli_fetch_assoc($result2);


$data = $row['new_order'] . '-машина не найдена-' . date('H:i d/m/Y') . ';' . $row2['history'];

// добавляем заказ в историю
mysqli_query($lnk, "UPDATE users SET new_order = '', history = '$data'  WHERE session_id = '$id_user'");

//удаляем заказ у водителя
if ($row['status_order'] != 'Идёт поиск машин') {
    $penalty = $cost * 0.3;
    $data = $row['new_order'] . '-штраф за несвоевременную отмену заказа ' . $penalty . 'р.-' . date('H:i d/m/Y') . ';' . $row['history'];
    mysqli_query($lnk, "UPDATE driver SET new_order = '', id_user = '', 
    status_order = '', history = '$data' WHERE id_user = '$id_user'");
} else {
    mysqli_query($lnk, "UPDATE driver SET new_order = '', id_user = '', 
    status_order = '' WHERE id_user = '$id_user'");
}
