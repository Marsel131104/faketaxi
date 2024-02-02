<?php
session_start();

if ((!isset($_SESSION['driver'])) or (isset($_SESSION['driver']) and !in_array(session_id(), $_SESSION['driver']))) {
    header('Location: ../index.php');
    die();
}


require_once("../db/db_connect.php");



$session_id = $_POST['session_id'];
$cost = $_POST['cost'];
//получаем данные заказа
$result = mysqli_query($lnk, "SELECT new_order, id_user, status_order, history FROM driver WHERE session_id = '$session_id'");
$row = mysqli_fetch_assoc($result);
$new_order = $row['new_order'];
$id_user = $row['id_user'];
$status_order = $row['status_order'];



$result2 = mysqli_query($lnk, "SELECT session_id FROM driver WHERE free = 1 AND new_order = ''");
$items = array();
while ($row2 = mysqli_fetch_assoc($result2)) {
    array_push($items, $row2);
}


foreach ($items as $item) {

    $id_new = $item['session_id'];
    mysqli_query($lnk, "UPDATE driver SET new_order = '$new_order', 
    id_user = '$id_user', status_order = 'Идёт поиск машин' WHERE session_id = '$id_new'");
}

if ($status_order != 'Идёт поиск машин') {
    $penalty = $cost * 0.3;
    $data = $row['new_order'] . '-штраф за несвоевременную отмену заказа ' . $penalty . ';' . $row['history'];
    mysqli_query($lnk, "UPDATE driver SET new_order = '', 
    id_user = '', status_order = '', history = '$data' WHERE session_id = '$session_id'");
} else {
    mysqli_query($lnk, "UPDATE driver SET new_order = '', id_user = '', status_order = '' WHERE session_id = '$session_id'");
}
