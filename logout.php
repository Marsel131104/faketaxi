<?php
session_start();
require_once "db/db_connect.php";

$session_id = session_id();

if (strpos($_SERVER['HTTP_REFERER'], 'passenger')) {

    $result = mysqli_query($lnk, "SELECT new_order FROM users WHERE session_id = '$session_id'");
    $row = mysqli_fetch_assoc($result);
    if (empty($row['new_order']))
        mysqli_query($lnk, "UPDATE users SET session_id = '' WHERE session_id = '$session_id'");

    $_SESSION['user'] = array_diff($_SESSION['user'], array(session_id()));
}

else if (strpos($_SERVER['HTTP_REFERER'], 'driver'))
    $_SESSION['driver'] = array_diff($_SESSION['driver'], array(session_id()));

header("Location: index.php");
