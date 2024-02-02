<?php
session_start();
if ((!isset($_SESSION['user'])) or (isset($_SESSION['user']) and !in_array(session_id(), $_SESSION['user']))) {
    header('Location: ../index.php');
    die();
}

require_once("../db/db_connect.php");


$session_id = session_id();

$result = mysqli_query($lnk, "SELECT new_order FROM users WHERE session_id = '$session_id'");
$row = mysqli_fetch_assoc($result);


?>

<!DOCTYPE html>
<html>

<head>
    <title>Сервис заказа такси</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body style="background-color: #f9ca24">

<header>
    <a href="main.php" class="logo">FAKETAXI</a>
    <nav>
        <ul>
            <li><a href="profile.php">Профиль</a></li>
            <?php
            if ($row['new_order']) echo '<li><a href="active_orders.php">Активные поездки <span class="badge bg-danger">1</span></a></li>';
            else echo '<li><a href="active_orders.php">Активные поездки</a></li>';
            ?>

            <li><a href="history_orders.php">История поездок</a></li>
            <li><a href="tariffs.php">Тарифы</a></li>
        </ul>

    </nav>
</header>


    <h4 style="text-align: center; margin-top: 20px;"><span class="badge bg-secondary">Цена за километр: 50р<br /><br />
            < 1км: 100р</span>
    </h4>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>