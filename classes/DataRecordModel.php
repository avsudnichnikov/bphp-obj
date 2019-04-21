<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 17.04.2019
 * Time: 18:21
 */

class DataRecordModel
{
    private $data;
    private $guid;

    public function __construct()
    {
        $this->data = new JsonObjDataModel(strtolower(static::class));
    }

    public function myself()
    {
        $this->data->query()->byGuid($this->guid);
        return $this->data;
    }

    public function create()
    {
        $this->data->add($this);
        $this->myself();
    }

    public function getGuid()
    {
        return $this->guid;
    }

    public function data()
    {
        return $this->data;
    }

    public function commit()
    {
        $this->data->save();
    }

    public function findFirst($param, $value, $findLike = false)
    {
        $this->data->query()->find($param, $value, $findLike)->first();
        return $this->data;
    }
}