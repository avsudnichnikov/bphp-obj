<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 17.04.2019
 * Time: 18:21
 */

abstract class DataRecordModel
{
    private $filename;
    private $guid;

    public function __construct()
    {
        $this->filename = strtolower(static::class) . 's';
        echo $this->filename;
    }

    public function commit()
    {
        $data = new Collection($this->filename);
        if (is_null($this->guid)){
            $this->guid = $data->add($this);
        } else {
            $data->changeObjByGuid($this->guid,$this);
        }
        $data->save();
    }

    public function delete()
    {
        $data = new Collection($this->filename);
        if (!is_null($this->guid)){
            $this->guid = $data->byGuid($this->guid)->delete();
        }
        $data->save();
    }
}