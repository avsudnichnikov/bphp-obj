<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 19.04.2019
 * Time: 14:47
 */

spl_autoload_register(function ($class_name){
    include $_SERVER['DOCUMENT_ROOT'] . "/classes/$class_name.php";
});