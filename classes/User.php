<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 18.04.2019
 * Time: 23:56
 */

class User extends DataRecordModel
{
    public $name;
    public $password;
    public $email;
    public $rate;

    public function addUserFromForm(){
        $this->create();
        $this->name = $_POST['name'];
        $this->password = $_POST['password'];
        $this->email = $_POST['email'];
        $this->rate = (int)$_POST['rate'];
        $this->commit();
    }

    public function displaySortedList(){
        foreach ($this->data()->newQuery()->orderBy('name')->getObjs() as $item){
            echo '<div>' . '<h3>'. $item->name .'</h3>' . '</div>';
            echo '<div>e-mail: ' . $item->email  . '</div>';
            echo '<div>rate: ' . $item->rate  . '</div>' . '<hr>';
        }
    }
}