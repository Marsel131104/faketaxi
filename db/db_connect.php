<?php

$lnk = mysqli_connect("localhost", "root", "", "course") or die('Cannot connect to server');
mysqli_select_db($lnk, "course") or die('Cannot open database');
mysqli_set_charset($lnk, 'utf8');
