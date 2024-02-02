<?php
session_start();
if ((!isset($_SESSION['user'])) or (isset($_SESSION['user']) and !in_array(session_id(), $_SESSION['user']))) {
    header('Location: ../index.php');
    die();
}


require_once("../db/db_connect.php");

$session_id = session_id();

$result = mysqli_query($lnk, "SELECT new_order FROM users WHERE session_id = '$session_id'");
$items = array();
while ($row = mysqli_fetch_assoc($result)) {
    array_push($items, $row);
}

$status_order = '';
$cost = '';
if (!empty($items[0]['new_order'])) {
    $fields = explode("-", $items[0]['new_order']);
    $address1 = $fields[0];
    $address2 = $fields[1];
    $dist = $fields[2];
    $cost = $fields[3];
    $result2 = mysqli_query($lnk, "SELECT status_order FROM driver WHERE id_user = '$session_id'");
    $row2 = mysqli_fetch_assoc($result2);
    $status_order = $row2['status_order'];
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Активные поездки</title>
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
            <?php
            if (!empty($row['new_order'])) echo '<li><a href="active_orders.php">Активные поездки <span class="badge bg-danger">1</span></a></li>';
            else echo '<li><a href="active_orders.php">Активные поездки</a></li>';
            ?>

            <li><a href="history_orders.php">История поездок</a></li>
            <li><a href="tariffs.php">Тарифы</a></li>
        </ul>

    </nav>
</header>

    <input name="cost" value='<?= $cost ?>' hidden>
    <input name="session_id" value='<?= $session_id ?>' hidden>

    <div id="alert" class="alert alert-success" role="alert" style="text-align: center" hidden>
        Ваш заказ отменен!
    </div>

    <?php
    if (!empty($items[0]['new_order'])) {
        echo '<div class="card" style="background-color: #fefae0; border: 0px; margin: 0 auto; margin-top: 30px; text-align: center; width: 30%">';
        echo '<div class="card-body" >';
        echo '<h5 class="card-title" style="font-family: cursive">Заказ</h5>';
        echo '<p style="font-family: cursive">' . $address1 . ' - ' . $address2 . '</p>';
        echo '<p style="font-family: cursive">' . $dist . 'км - ' . $cost . 'р.</p>';
        if ($status_order == 'Заказ принят') echo '<p style="font-family: cursive">Статус: ' . $status_order . ', водитель направляется к вам</p>';
        else if ($status_order == 'На месте') echo '<p style="font-family: cursive">Статус: Водитель подъехал, выходите</p>';
        else if ($status_order == 'Поездка начата') echo '<p style="font-family: cursive">Статус: В пути</p>';
        else echo '<p style="font-family: cursive">Статус: ' . $status_order . '</p>';

        if ($status_order != 'Поездка начата')
            echo '<button id="btn_cancel" type="button" class="btn btn-outline-danger btn-sm" style="float: left">Отменить</button>';
        echo '</div>';
        echo '</div>';
    }
    ?>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="active_orders.js"></script>
</body>

</html>