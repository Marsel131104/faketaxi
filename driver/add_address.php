<?php
include("../db/db_connect.php");



$id = $_POST['id'];
$value = $_POST['address'];
mysqli_query($lnk, "UPDATE driver SET address = (\"$value\") WHERE id = $id");
