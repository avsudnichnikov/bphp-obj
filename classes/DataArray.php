<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 18.04.2019
 * Time: 11:00
 */

class DataArray
{
    private $file;
    private $dataTitle;
    private $dataArray;
    private $query;

    const GUID_PREFIX = 'obj';

    public function __construct($dataModelName)
    {
        $this->file = new JsonFileAccessModel($dataModelName);
        $this->load();
    }

    public function load()
    {
        $this->dataTitle = $this->file->readJson()->dataTitle;
        $this->dataArray = (array)$this->file->readJson()->dataArray;
    }

    public function save()
    {
        if(!is_null($this->query)){
            $this->dataArray = array_merge($this->dataArray, $this->query);
        }
        $this->file->writeObjArr([
            'dataTitle' => $this->dataTitle,
            'dataArray' => $this->dataArray,
        ]);
    }

    public function insert($new_data)
    {
        $guid = self::GUID_PREFIX . ++$this->dataTitle->last_guid;
        $this->changeByGuid($guid, $new_data);
        $this->query = $this->getByGuid($guid);
        return $guid;
    }

    public function getFirstGuid($default = null)
    {
        foreach ($this->query as $key => $item) {
            return ['guid' => $key];
        }
        return $default;
    }

    public function getByGuid($guid)
    {
        if ($this->query[$guid] !== null) {
            return [$guid => $this->query[$guid]];
        }
        return null;
    }

    public function byGuid($guid)
    {
        $this->query = [$guid => $this->query[$guid]];
        return $this;
    }

    public function changeByGuid($guid, $new_data)
    {
        $this->dataArray[$guid] = $new_data;
    }

    public function deleteByGuid($guid)
    {
        unset($this->query[$guid]);
        unset($this->dataArray[$guid]);
    }

    public function query()
    {
        $this->query = $this->dataArray;
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }


    public function where($param, $value)
    {
        $this->query = [];
        foreach ($this->dataArray as $key => $record) {
            if ($record->$param == $value) {
                $this->query[$key] = $record;
            };
        };
        return $this;
    }

    public function updateQuery($param, $new_value)
    {
        foreach ($this->query as $key => $record) {
            $this->dataArray[$key]->$param = $new_value;
        };
    }

    public function deleteQuery()
    {
        foreach ($this->query as $key => $record) {
            unset($this->query[$key]);
            unset($this->dataArray[$key]);
        };
    }
}