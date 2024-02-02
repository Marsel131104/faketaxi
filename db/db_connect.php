<?php

$lnk = @mysqli_connect("localhost", "root", "", "f0915103_faketaxi") or die('Cannot connect to server');
mysqli_select_db($lnk, "f0915103_faketaxi") or die('Cannot open database');
mysqli_set_charset($lnk, 'utf8');
