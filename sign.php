<?php
session_start();

?>

<html>

<head>
    <title>Форма входа</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body class="body_index_sign" style="background-image: url('https://images.wallpaperscraft.ru/image/single/taksi_slovo_nadpis_180873_1680x1050.jpg')">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <form method="POST" class="form_index_sign" action="check.php">
        <div class="reg">
            <h1><font color="#ffffff">Вход</h1>
        </div>


        <div class="mb-3">
            <label for="validationServer04" class="form-label"><font color="#ffffff">Роль</label>
            <select class="form-select" id="validationServer04" aria-describedby="validationServer04Feedback" required name="value_select">
                <option value="">Выберите...</option>
                <option>Таксист</option>
                <option>Пассажир</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label"><font color="#ffffff">Адрес электронной почты</label>
            <input type="email" class="form-control" name="value_email">
        </div>
        <?php
        if (isset($_GET["person"]))
            echo "<div><font size='3'><i>Пользователь с такой почтой не найден</i></font></div>";
        ?>

        <div class="mb-3">
            <label class="form-label"><font color="#ffffff">Пароль</label>
            <input type="password" class="form-control" name="value_password">
        </div>

        <button type="submit" class="btn btn-dark">Войти</button>
        <?php
        if (isset($_GET["accuracy"]))
            echo "<div><font size='3'><i>Заполните все поля</i></font></div>";
        else if (isset($_GET["password_error"]))
            echo "<div><font size='3'><i>Неверный пароль</i></font></div>"
        ?>


        <br>

        <div class="link">
            <p><a href="index.php" class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Зарегистрироваться</a></p>
        </div>
    </form>


</body>


</html>