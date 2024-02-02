<?php
session_start();


if ((!isset($_SESSION['driver'])) or (isset($_SESSION['driver']) and !in_array(session_id(), $_SESSION['driver']))) {
    header('Location: ../index.php');
    die();
}

require_once("../db/db_connect.php");

$result = mysqli_query($lnk, "SELECT session_id FROM driver WHERE free = 1 AND new_order='' ");
$items = array();
while ($row = mysqli_fetch_assoc($result)) {
    array_push($items, $row);
}
echo json_encode($items);
