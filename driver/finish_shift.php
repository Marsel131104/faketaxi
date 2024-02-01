<?php

include("../db/db_connect.php");



$id = $_POST['id'];
$result = mysqli_query($lnk, "UPDATE driver SET free = 0 WHERE id = $id");
mysqli_query($lnk, "UPDATE driver SET address = '' WHERE id = $id");
