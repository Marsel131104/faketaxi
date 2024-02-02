<?php
session_start();
if ((!isset($_SESSION['user'])) or (isset($_SESSION['user']) and !in_array(session_id(), $_SESSION['user']))) {
    header('Location: ../index.php');
    die();
}

require_once("../db/db_connect.php");

$session_id = session_id();

$result = mysqli_query($lnk, "SELECT new_order FROM users WHERE session_id = '$session_id'");
$row = mysqli_fetch_assoc($result);
echo json_encode($row);
