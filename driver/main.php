<?php
include("../db/db_connect.php");

$id = $_GET['id'];
$result = mysqli_query($lnk, "SELECT address, new_order, id_user, status_order FROM driver WHERE id = $id");
$row = mysqli_fetch_assoc($result);

$address = $row['address'];
$address1 = '';
$address2 = '';
$id_user = '';
$status_order = '';
$cost = '';
if (!empty($row['new_order'])) {
    $fields = explode("-", $row['new_order']);
    $address1 = $fields[0];
    $address2 = $fields[1];
    $dist = $fields[2];
    $cost = $fields[3];
    $id_user = $row['id_user'];
    $status_order = $row['status_order'];
}
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
        <a href="main.php?id=<?= $id ?>" class="logo">FAKETAXI</a>
        <nav>
            <ul>
                <li><a href="profile.php?id=<?= $id ?>">Профиль</a></li>
                <li><a href="history_orders?id=<?= $id ?>">История поездок</a></li>
                <li><a href="tariffs.php?id=<?= $id ?>">Тарифы</a></li>
            </ul>

        </nav>
    </header>

    <input name="id" value='<?= $id ?>' hidden>
    <input name="address" value='<?= $address ?>' hidden>
    <input name="address1" value='<?= $address1 ?>' hidden>
    <input name="address2" value='<?= $address2 ?>' hidden>
    <input name="id_user" value='<?= $id_user ?>' hidden>
    <input name="status_order" value='<?= $status_order ?>' hidden>
    <input name="cost" value='<?= $cost ?>' hidden>




    <div id="div_form">
        <form id="form_order">
            <?php
            if (!empty($row['new_order']) && $row['status_order'] != '' && $row['status_order'] != 'Идёт поиск машин') echo "<u><span id='actual_address' style='font-family: cursive'>Вы выполняете заказ</span></u>";
            else if (!empty($row['address'])) echo "<u><span id='actual_address' style='font-family: cursive'>Ваш текущий адрес: " . $row['address'] . "</span></u>";
            else echo "<u><span id='actual_address' style='font-family: cursive'>Ваш текущий адрес: </span></u>";
            ?>


            <div style="margin-top: 50px" class="mb-3">
                <?php
                if (!empty($row['new_order'])) {
                    echo '<div class="card" style="background-color: #fefae0; border: 0px;  margin-top: 20px; text-align: center">';
                    echo '<div class="card-body" >';
                    if ($status_order == 'Идёт поиск машин')
                        echo '<h5 style="font-family: cursive" class="card-title">Вам пришел заказ</h5>';
                    else
                        echo '<h5 style="font-family: cursive" class="card-title">Заказ</h5>';
                    echo '<p style="font-family: cursive" class="card-text">' . $address1 . ' - ' . $address2 . '</p>';
                    echo '<p style="font-family: cursive" class="card-text">' . $dist . 'км - ' . $cost . 'р.</p>';
                    echo '<p id="p_status" style="font-family: cursive"></p>';
                    if ($status_order == 'Идёт поиск машин')
                        echo '<button id="btn_ok" type="button" class="btn btn-success btn-sm" style="float: left">Принять</button>';
                    else if ($status_order == 'Заказ принят')
                        echo '<button id="btn_ok" type="button" class="btn btn-success btn-sm" style="float: left">На месте</button>';
                    else if ($status_order == 'На месте')
                        echo '<button id="btn_ok" type="button" class="btn btn-success btn-sm" style="float: left">Начать поездку</button>';
                    else if ($status_order == 'Поездка начата')
                        echo '<button id="btn_ok" type="button" class="btn btn-success btn-sm" style="float: left">Завершить поездку</button>';
                    if ($status_order == 'Идёт поиск машин')
                        echo '<button id="btn_cancel" type="button" class="btn btn-outline-danger btn-sm" style="float: right">Отклонить</button>';
                    else if ($status_order != 'Поездка начата')
                        echo '<button id="btn_cancel" type="button" class="btn btn-outline-danger btn-sm" style="float: right">Отменить</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '<label class="form-label" hidden>Заменить / Установить адрес с которого хотите начать</label>';
                    echo '<input id="address" type="text" class="form-control" hidden>';
                    echo '<button id="btn" type="button" class="btn btn-dark" style="margin-top: 20px" hidden>Запомнить адрес</button>';
                } else if (empty($row['new_order'])) {
                    echo '<label class="form-label">Заменить / Установить адрес с которого хотите начать</label>';
                    echo '<input type="text" class="form-control" name="address">';
                    echo '<button id="btn" type="button" class="btn btn-dark" style="margin-top: 20px">Запомнить адрес</button>';
                }
                ?>

            </div>

            <div style="margin-top: 10px"><i id="err"></i></div>
            <div id="success_string"></div>

        </form>

        <div id="map"></div>

    </div>
    <div id="alert" class="alert alert-success" role="alert" style="text-align: center; top: 84px" hidden>
        Заказ отменен
    </div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="script.js"></script>
</body>

</html>