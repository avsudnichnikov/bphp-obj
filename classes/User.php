<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 18.04.2019
 * Time: 23:56
 */

class User extends DataModel
{
    public $id;
    public $name;

    public function __construct()
    {
        parent::__construct();
    }
}