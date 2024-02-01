<?php

include("../db/db_connect.php");

$id = $_POST['id'];
$new_order = $_POST['new_order'];
$id_user = $_POST['id_user'];
$status_order = $_POST['status_order'];

mysqli_query($lnk, "UPDATE driver SET new_order = '$new_order', 
id_user = '$id_user', status_order = '$status_order' WHERE id = $id");
mysqli_query($lnk, "UPDATE users SET new_order = '$new_order' WHERE id = $id_user");
