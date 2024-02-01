<?php

include("../db/db_connect.php");

$id = $_GET['id'];


$result = mysqli_query($lnk, "SELECT name, surname, email, new_order FROM users WHERE id=$id");
$row = mysqli_fetch_assoc($result);
?>


<!DOCTYPE html>
<html>

<head>
    <title>Профиль</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body style="background-color: #f9ca24; ">

    <header>
        <a href="main.php?id=<?= $id ?>" class="logo">FAKETAXI</a>
        <nav>
            <ul>
                <li><a href="profile.php?id=<?= $id ?>">Профиль</a></li>
                <?php
                if ($row['new_order']) echo '<li><a href="active_orders.php?id=' . $id . '">Активные поездки <span class="badge bg-danger">1</span></a></li>';
                else echo '<li><a href="active_orders.php?id=' . $id . '">Активные поездки</a></li>';
                ?>

                <li><a href="history_orders.php?id=<?= $id ?>">История поездок</a></li>
                <li><a href="tariffs.php?id=<?= $id ?>">Тарифы</a></li>
            </ul>

        </nav>
    </header>

    <input name="id" value='<?= $id ?>' hidden>


    <div style="text-align: center;">
        <h4 style="text-align: center; margin-top: 20px; display: inline-block;"><span class="badge bg-success">Ваши личные данные</span></h4>
        <a href="/taxi/registration/logout.php" class="link-danger" style="float: right; margin-top: 20px; margin-right: 20px;">Выйти</a>
    </div>




    <div id="data" style="margin-top: 50px; margin-left: 10%;">
        <div class="col-md" style="margin-bottom: 20px;">
            <div class="form-floating" style="width: 35%">
                <input type="text" class="form-control" name='role' value="Пассажир" disabled>
                <label for="floatingInputGrid">Роль</label>
            </div>
        </div>

        <div class="col-md" style="margin-bottom: 20px;">
            <div class="form-floating" style="width: 35%">
                <input type="text" class="form-control" name='name' value=<?php echo $row['name'] ?> disabled>
                <label for="floatingInputGrid">Имя</label>
            </div>
        </div>

        <div class="col-md" style="margin-bottom: 20px;">
            <div class="form-floating" style="width: 35%">
                <input type="text" class="form-control" name='surname' value=<?php echo $row['surname'] ?> disabled>
                <label for="floatingInputGrid">Фамилия</label>
            </div>
        </div>

        <div class="col-md" style="margin-bottom: 20px;">
            <div class="form-floating" style="width: 35%">
                <input type="email" class="form-control" name='email' value=<?php echo $row['email'] ?> disabled>
                <label for="floatingInputGrid">Email</label>
            </div>
        </div>

        <div class="btn-group" hidden>
            <button id="btn_ok" type="button" class="btn btn-success">Сохранить</button>
            <button id="btn_cancel" type="button" class="btn btn-danger">Отмена</button>
        </div>
        <button id="btn_change" type="button" class="btn btn-dark">Редактировать</button>
        <div style="margin-top: 10px"><i></i></div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="profile.js"></script>
</body>

</html>