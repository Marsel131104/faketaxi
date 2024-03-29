<?php
session_start();

if ((!isset($_SESSION['driver'])) or (isset($_SESSION['driver']) and !in_array(session_id(), $_SESSION['driver']))) {
    header('Location: ../index.php');
    die();
}



require_once("../db/db_connect.php");

$session_id = session_id();
$result = mysqli_query($lnk, "SELECT new_order, history FROM driver WHERE session_id = '$session_id'");
$row = mysqli_fetch_assoc($result);
if (!empty($row['history'])) {
    $fields = explode(';', $row['history']);
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>История поездок</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://api-maps.yandex.ru/2.1/?apikey=5212896e-bcc0-42f3-b22e-a273ed990198&lang=ru_RU" type="text/javascript"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body style="background-color: #f9ca24">

<header>
    <a href="main.php" class="logo">FAKETAXI</a>
    <nav>
        <ul>
            <li><a href="profile.php">Профиль</a></li>
            <li><a href="history_orders.php">История поездок</a></li>
            <li><a href="tariffs.php">Тарифы</a></li>
        </ul>

    </nav>
</header>

    <?php
    if (!empty($row['history'])) {
        foreach ($fields as $field) {
            if (!empty($field)) {
                $orders = explode("-", $field);
                $address1 = $orders[0];
                $address2 = $orders[1];
                $dist = $orders[2];
                $cost = $orders[3];
                $status = $orders[4];
                $date = $orders[5];
                if ($status != 'поездка завершена')
                    echo '<div class="card" style="background-color: #fba2a2; border: 0px; margin: 0 auto; margin-top: 30px; text-align: center; width: 35%">';
                else
                    echo '<div class="card" style="background-color: #adfaa2; border: 0px; margin: 0 auto; margin-top: 30px; text-align: center; width: 35%">';
                echo '<div class="card-body" >';
                echo '<h5 class="card-title" style="font-family: Verdana">Заказ</h5>';
                echo '<p style="font-family: Verdana">' . $address1 . ' - ' . $address2 . '</p>';
                echo '<p style="font-family: Verdana">' . $dist . 'км - ' . $cost . 'р.</p>';
                echo '<p style="font-family: Verdana">Статус: ' . $status . '</p>';
                echo '<p style="font-family: Verdana">' . $date . '</p>';
                echo '</div>';
                echo '</div>';
            }
        }
    }
    ?>







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</body>

</html>