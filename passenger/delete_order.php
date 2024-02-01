<?php
// удаление заказа пассажиром

include("../db/db_connect.php");

date_default_timezone_set('Europe/Moscow');

$id = $_POST['id'];
$cost = $_POST['cost'];


$result = mysqli_query($lnk, "SELECT new_order, status_order, history FROM driver WHERE id_user = $id");
$row = mysqli_fetch_assoc($result);

// проверка статуса заказа
if (!empty($row['status_order'])) {
    $status_order = $row['status_order'];


    $result2 = mysqli_query($lnk, "SELECT history FROM users WHERE id = $id");
    $row2 = mysqli_fetch_assoc($result2);

    if ($status_order == 'Идёт поиск машин') {

        $data = $row['new_order'] . '-заказ отменен-' . date('H:i d/m/Y') . ';' . $row2['history'];

        mysqli_query($lnk, "UPDATE users SET new_order = '', history = '$data' WHERE id = $id");
        mysqli_query($lnk, "UPDATE driver SET new_order = '', id_user = '', status_order = '' WHERE id_user = $id");
    } else if ($status_order == 'Заказ принят') {



        $data1 = $row['new_order'] . '-заказ отменен-' . date('H:i d/m/Y') . ';' . $row2['history'];
        $data2 = $row['new_order'] . '-заказ отменен пассажиром-' . date('H:i d/m/Y') . ';' . $row['history'];

        mysqli_query($lnk, "UPDATE users SET new_order = '', history = '$data1' WHERE id = $id");
        mysqli_query($lnk, "UPDATE driver SET new_order = '', id_user = '', status_order = '', history = '$data2' WHERE id_user = $id");
    } else if ($status_order == 'На месте') {


        $penalty = $cost * 0.3;

        $data = $row['new_order'] . '-штраф за несвоевременную отмену заказа ' . $penalty . 'р.-' . date('H:i d/m/Y') . ';' . $row2['history'];
        mysqli_query($lnk, "UPDATE users SET new_order = '', history = '$data' WHERE id = $id");


        $data = $row['new_order'] . '-заказ отменен пассажиром ' . $penalty . 'р.-' . date('H:i d/m/Y') . ';' . $row['history'];
        mysqli_query($lnk, "UPDATE driver SET new_order = '', id_user = '', status_order = '', history = '$data'  WHERE id_user = $id");
    } else {
        echo 'Заказ нельзя отменить когда водитель начал поездку';
    }
} else echo 'Машин не нашлось, ваш заказ отменен';
