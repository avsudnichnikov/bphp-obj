<?
require './autoloadClass.php';
require './config/SystemConfig.php';
require './prettyPrint.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>bPHP - 2.3</title>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h3>Создать пользователя</h3>
    <form action="forms/addNewUser.php" method="POST">
        <label for="name">Имя: </label><input type="text" name="name" /><br><br>
        <label for="password">Пароль: </label><input type="text" name="password" /><br><br>
        <label for="email">Электронная почта: </label><input type="text" name="email" /><br><br>
        <label for="rate">Рейтинг: </label><input type="text" name="rate" /><br><br>
        <input type="submit" value="Войти">
    </form>
    <hr>
    <?
        $user = new User();
        $user->displaySortedList();
    ?>
</body>
</html>