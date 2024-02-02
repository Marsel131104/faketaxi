<?php
session_start();
print_r($_SESSION);

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
                if ($row['new_order']) echo '<li><a href="active_orders.php">Активные поездки <span class="badge bg-danger">1</span></a></li>';
                else echo '<li><a href="active_orders.php">Активные поездки</a></li>';
                ?>

                <li><a href="history_orders.php">История поездок</a></li>
                <li><a href="tariffs.php">Тарифы</a></li>
            </ul>

        </nav>
    </header>

    <input name="session_id" value='<?= $session_id ?>' hidden>


    <h4 style="text-align: center; margin-top: 20px;"><span class="badge bg-success">Сервис по заказу такси в СПб</span></h4>

    <div>




        <div id="div_form">
            <form id="form_order">
                <div class="mb-3">
                    <label class="form-label">Адрес отправления</label>
                    <input type="text" class="form-control" name="address1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Адрес прибытия</label>
                    <input type="text" class="form-control" name="address2">
                </div>

                <div class="btn-group" hidden>
                    <button id="btn_ok" type="button" class="btn btn-success">Заказать</button>
                    <button id="btn_cancel" type="button" class="btn btn-danger">Отмена</button>
                </div>


                <button id="btn" type="button" class="btn btn-dark">Посмотреть на карте</button>

                <div style="margin-top: 10px"><i></i></div>
            </form>

            <div id="map"></div>
        </div>
        <h4 style="text-align: center; margin-top: 70px"><span id="free_cars" class="badge bg-secondary"></span></h4>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="script.js"></script>
</body>

</html>