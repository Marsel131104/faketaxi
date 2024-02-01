<?php

include("../db/db_connect.php");

$id = $_POST['id'];

$result = mysqli_query($lnk, "SELECT new_order FROM users WHERE id = $id");
$row = mysqli_fetch_assoc($result);
echo json_encode($row);
