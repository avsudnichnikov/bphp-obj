<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 17.04.2019
 * Time: 18:21
 */

class DataModel
{
    private $data;
    private $guid;

    public function __construct()
    {
        $this->data = new JsonObjDataModel(strtolower(static::class));
        $this->guid = $this->data->add($this);
    }

    public function myself()
    {
        return $this->data->all()->byGuid($this->guid)->get()[0];
    }

    public function getGuid()
    {
        return $this->guid;
    }

    public function getData()
    {
        return $this->data;
    }

    public function save()
    {
        $this->data->save();
    }

    public function findFirst($param, $value, $findLike = false)
    {
        $this->data->all()->find($param, $value, $findLike)->first();
    }
}