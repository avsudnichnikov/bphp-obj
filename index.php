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
        $test = new User();

        $test->name = 'Igor';
        $test->id = 15;

        pretty_print($test);
        var_dump($test->get());
        $test->commit();
//        echo '<hr>';
//        var_dump($test->select()->where('guid_0000000002','18-26-00'));
    ?>
</body>
</html>