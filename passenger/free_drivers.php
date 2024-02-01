<?php
include("../db/db_connect.php");

$result = mysqli_query($lnk, "SELECT id, address FROM driver WHERE free = 1 AND new_order='' ");
$items = array();
while ($row = mysqli_fetch_assoc($result)) {
    array_push($items, $row);
}
echo json_encode($items);
