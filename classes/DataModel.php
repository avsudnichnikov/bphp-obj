<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 17.04.2019
 * Time: 18:21
 */

class DataModel
{
    private $guid;
    private $data;

    public function __construct()
    {
        $this->data = new DataArray(strtolower(static::class));
        $this->create();
    }

    public function create()
    {
        $this->guid = $this->data->insert($this);
    }

    public function get()
    {
        return $this->data->query()->getByGuid($this->guid);
    }

    public function set($obj)
    {
        $this->data->changeByGuid($this->guid, $obj);
    }

    public function findFirst()
    {
        $this->guid = $this->data->query()->getFirstGuid();
        return $this->data[$this->guid];
    }

    public function query()
    {
        return $this->data->query();
    }

    public function delete()
    {
        $this->data->deleteByGuid($this->guid);
    }

    public function commit()
    {
        $this->data->save();
    }
}