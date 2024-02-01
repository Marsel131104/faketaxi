<?php
include("../db/db_connect.php");



$id = $_POST['id'];
$cost = $_POST['cost'];
//получаем данные заказа
$result = mysqli_query($lnk, "SELECT new_order, id_user, status_order, history FROM driver WHERE id = $id");
$row = mysqli_fetch_assoc($result);
$new_order = $row['new_order'];
$id_user = $row['id_user'];
$status_order = $row['status_order'];



$result2 = mysqli_query($lnk, "SELECT id FROM driver WHERE free = 1 AND new_order = ''");
$items = array();
while ($row2 = mysqli_fetch_assoc($result2)) {
    array_push($items, $row2);
}


foreach ($items as $item) {

    $id_new = $item['id'];
    mysqli_query($lnk, "UPDATE driver SET new_order = '$new_order', 
    id_user = '$id_user', status_order = 'Идёт поиск машин' WHERE id = $id_new");
}

if ($status_order != 'Идёт поиск машин') {
    $penalty = $cost * 0.3;
    $data = $row['new_order'] . '-штраф за несвоевременную отмену заказа ' . $penalty . ';' . $row['history'];
    mysqli_query($lnk, "UPDATE driver SET new_order = '', 
    id_user = '', status_order = '', history = '$data' WHERE id = $id");
} else {
    mysqli_query($lnk, "UPDATE driver SET new_order = '', id_user = '', status_order = '' WHERE id = $id");
}
