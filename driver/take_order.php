<?php

include("../db/db_connect.php");

date_default_timezone_set('Europe/Moscow');

$id = $_POST['id'];
$id_user = $_POST['id_user'];
$address2 = 'Санкт-Петербург, ' . $_POST['address2'];


$result = mysqli_query($lnk, "SELECT new_order, status_order, history FROM driver WHERE id = $id");
$row = mysqli_fetch_assoc($result);
if ($row['new_order']) {
    if ($row['status_order'] == 'Идёт поиск машин') {
        mysqli_query($lnk, "UPDATE driver SET status_order = 'Заказ принят' WHERE id = $id");
        mysqli_query($lnk, "UPDATE driver SET new_order = '', id_user = '', status_order = '' 
        WHERE id_user = $id_user AND id != $id");
        echo 'Заказ принят';
    } else if ($row['status_order'] == 'Заказ принят') {
        mysqli_query($lnk, "UPDATE driver SET status_order = 'На месте' WHERE id = $id");
        echo 'На месте';
    } else if ($row['status_order'] == 'На месте') {
        mysqli_query($lnk, "UPDATE driver SET status_order = 'Поездка начата' WHERE id = $id");
        echo 'Поездка начата';
    } else if ($row['status_order'] == 'Поездка начата') {
        $result2 = mysqli_query($lnk, "SELECT history FROM users WHERE id = $id_user");
        $row2 = mysqli_fetch_assoc($result2);

        $data = $row['new_order'] . '-поездка завершена-' . date('H:i d/m/Y') . ';' . $row['history'];
        mysqli_query($lnk, "UPDATE driver SET address = '$address2', new_order = '', id_user = '', status_order = '', history = '$data' WHERE id = $id");

        $data2 = $row['new_order'] . '-поездка завершена-' . date('H:i d/m/Y') . ';' . $row2['history'];
        mysqli_query($lnk, "UPDATE users SET new_order = '', history = '$data2' WHERE id = $id_user");
        echo 'Поездка завершена';
    }
} else echo 'Заказ был отменен или принят другим водителем';
