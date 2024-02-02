<?php
session_start();
if ((!isset($_SESSION['user'])) or (isset($_SESSION['user']) and !in_array(session_id(), $_SESSION['user']))) {
    header('Location: ../index.php');
    die();
}

require_once("../db/db_connect.php");

$session_id = $_POST['session_id'];
$new_order = $_POST['new_order'];
$id_user = $_POST['id_user'];
$status_order = $_POST['status_order'];




mysqli_query($lnk, "UPDATE driver SET new_order = '$new_order', 
id_user = '$id_user', status_order = '$status_order' WHERE session_id = '$session_id'");
mysqli_query($lnk, "UPDATE users SET new_order = '$new_order' WHERE session_id = '$id_user'");
