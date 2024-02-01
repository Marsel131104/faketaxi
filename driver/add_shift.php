<?php
include("../db/db_connect.php");



$id = $_POST['id'];
$result = mysqli_query($lnk, "UPDATE driver SET free = 1 WHERE id = $id");
