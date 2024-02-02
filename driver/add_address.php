<?php
session_start();


if ((!isset($_SESSION['driver'])) or (isset($_SESSION['driver']) and !in_array(session_id(), $_SESSION['driver']))) {
    header('Location: ../index.php');
    die();
}


require_once("../db/db_connect.php");



$session_id = $_POST['session_id'];
$value = $_POST['address'];
mysqli_query($lnk, "UPDATE driver SET address = (\"$value\") WHERE session_id = '$session_id'");
