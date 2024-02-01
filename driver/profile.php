<?php
include("../db/db_connect.php");

$id = $_GET['id'];
$result = mysqli_query($lnk, "SELECT name, surname, email, free, address, new_order FROM driver WHERE id = $id");
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
                <li><a href="profile.php??id=<?= $id ?>">Профиль</a></li>
                <li><a href="history_orders?id=<?= $id ?>">История поездок</a></li>
                <li><a href="tariffs.php?id=<?= $id ?>">Тарифы</a></li>
            </ul>

        </nav>
    </header>

    <input name="id" value='<?= $id ?>' hidden>
    <input id="hidden_input" hidden value="<?= $row['address'] ?>">
    <input id="hidden_input_new-order" hidden value="<?= $row['new_order'] ?>">


    <div style="text-align: center;">
        <h4 style="text-align: center; margin-top: 20px; display: inline-block;"><span class="badge bg-success">Ваши личные данные</span></h4>
        <a href="/taxi/registration/sign.php" class="link-danger" style="float: right; margin-top: 20px; margin-right: 20px;">Выйти</a>
    </div>




    <div id="data" style="margin-top: 50px; margin-left: 10%;">
        <div style="display: inline-block; width: 40%">
            <div class="col-md" style="margin-bottom: 20px;">
                <div class="form-floating" style="width: 100%">
                    <input type="text" class="form-control" name='role' value="Таксист" disabled>
                    <label for="floatingInputGrid">Роль</label>
                </div>
            </div>

            <div class="col-md" style="margin-bottom: 20px;">
                <div class="form-floating" style="width: 100%">
                    <input type="text" class="form-control" name='name' value=<?= $row['name'] ?> disabled>
                    <label for="floatingInputGrid">Имя</label>
                </div>
            </div>

            <div class="col-md" style="margin-bottom: 20px;">
                <div class="form-floating" style="width: 100%">
                    <input type="text" class="form-control" name='surname' value=<?= $row['surname'] ?> disabled>
                    <label for="floatingInputGrid">Фамилия</label>
                </div>
            </div>

            <div class="col-md" style="margin-bottom: 20px;">
                <div class="form-floating" style="width: 100%">
                    <input type="email" class="form-control" name='email' value=<?= $row['email'] ?> disabled>
                    <label for="floatingInputGrid">Email</label>
                </div>
            </div>

            <div class="btn-group" hidden>
                <button id="btn_ok" type="button" class="btn btn-success">Сохранить</button>
                <button id="btn_cancel" type="button" class="btn btn-danger">Отмена</button>
            </div>
            <button id="btn_change" type="button" class="btn btn-dark">Редактировать</button>
            <div style="margin-top: 10px" id="top_i"><i></i></div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 50px">
        <?php
        if ($row['free'] == 0) {
            echo '<button id="start_shift" type="button" class="btn btn-outline-success">Нажмите чтобы начать смену</button>';
            echo '<button id="finish_shift" type="button" class="btn btn-danger" hidden>У вас есть активная смена, нажмите чтобы уйти со смены</button>';
            echo '<div style="margin-top: 10px"><i id="bottom_i"></i></div>';
        } else {
            echo '<button id="start_shift" type="button" class="btn btn-outline-success" hidden>Нажмите чтобы начать смену</button>';
            echo '<button id="finish_shift" type="button" class="btn btn-danger">У вас есть активная смена, нажмите чтобы уйти со смены</button>';
            echo '<div style="margin-top: 10px"><i id="bottom_i"></i></div>';
        }
        ?>
    </div>







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="profile.js"></script>
</body>

</html>