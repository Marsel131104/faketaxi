<?php
include("../db/db_connect.php");

$id = $_GET['id'];
$result = mysqli_query($lnk, "SELECT new_order FROM users WHERE id = $id");
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
        <a href="main.php?id=<?= $id ?>" class="logo">FAKETAXI</a>
        <nav>
            <ul>
                <li><a href="profile.php?id=<?= $id ?>">Профиль</a></li>
                <li><a href="history_orders?id=<?= $id ?>">История поездок</a></li>
                <li><a href="tariffs.php?id=<?= $id ?>">Тарифы</a></li>
            </ul>

        </nav>
    </header>


    <h4 style="text-align: center; margin-top: 20px;"><span class="badge bg-secondary">Цена за километр: 50р<br /><br />
            < 1км: 100р</span>
    </h4>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>