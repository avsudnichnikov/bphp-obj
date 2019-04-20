<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 17.04.2019
 * Time: 18:21
 */

class JsonFileAccessModel extends FileAccessModel
{
    public function __construct($dataModelName)
    {
        parent::__construct(Config::DATABASE_PATH . $dataModelName . 's.json');
    }

    public function readJson()
    {
        return json_decode($this->read());
    }

    public function writeJson($json)
    {
        return $this->write(json_encode($json, JSON_PRETTY_PRINT));
    }

    public function writeObjArr($array = [])
    {
        return $this->writeJson($array);
    }

}