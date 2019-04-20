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
    <?
        $user = new User();
        $user->name = 'Aang';
        //$user->save();
        pretty_print($user->getData()->all()->get());
    ?>
</body>
</html>