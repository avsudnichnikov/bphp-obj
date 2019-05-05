<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 05.05.2019
 * Time: 23:44
 */

class Users extends Collection
{
    public function displaySortedList(){
        foreach ($this->newQuery()->orderBy('name')->getObjs() as $item){
            echo '<div>' . '<h3>'. $item->name .'</h3>' . '</div>';
            echo '<div>e-mail: ' . $item->email  . '</div>';
            echo '<div>rate: ' . $item->rate  . '</div>' . '<hr>';
        }
    }
}