<?php
session_start();
if ((!isset($_SESSION['user'])) or (isset($_SESSION['user']) and !in_array(session_id(), $_SESSION['user']))) {
    header('Location: ../index.php');
    die();
}
require_once("../db/db_connect.php");

$result = mysqli_query($lnk, "SELECT session_id, address FROM driver WHERE free = 1 AND new_order='' ");
$items = array();
while ($row = mysqli_fetch_assoc($result)) {
    array_push($items, $row);
}
echo json_encode($items);
