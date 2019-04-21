<?
require $_SERVER['DOCUMENT_ROOT'] . '/autoloadClass.php';
require $_SERVER['DOCUMENT_ROOT'] . '/config/SystemConfig.php';
require $_SERVER['DOCUMENT_ROOT'] . '/prettyPrint.php';

if ((isset($_POST['name'])) &&
    (isset($_POST['password'])) &&
    (isset($_POST['email'])) &&
    (isset($_POST['rate']))
){
    $new_user = new User();
    $new_user->addUserFromForm();

}

header('HTTP/1.1 200 OK');
header('Location: http://'.$_SERVER['HTTP_HOST']);

