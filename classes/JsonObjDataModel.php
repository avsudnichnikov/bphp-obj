<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 18.04.2019
 * Time: 11:00
 */

class JsonObjDataModel
{
    private $file;
    private $dataTitle;
    private $dataArray;
    private $query;

    const GUID_PREFIX = 'o';
    const SORT_DIRECTION_FORWARD = true;
    const SORT_DIRECTION_BACKWORD = false;

    public function __construct($dataModelName)
    {
        $this->file = new JsonFileAccessModel($dataModelName);
        $this->load();
    }

    public function load()
    {
        $this->dataTitle = $this->file->readJson()->dataTitle;
        $this->dataArray = (array)$this->file->readJson()->dataArray;
        $this->all();
    }

    public function save()
    {
        $this->file->writeJSON([
            'dataTitle' => $this->dataTitle,
            'dataArray' => $this->dataArray,
        ]);
    }

    public function all()
    {
        $this->query = array_keys($this->dataArray);
        return $this;
    }

    public function query()
    {
        return $this->query;
    }

    public function get()
    {
        $result = [];
        foreach ($this->query as $guid) {
            $result[$guid] = $this->dataArray[$guid];
        }
        return $result;
    }

    public function find($param, $value, $findLike = false)
    {
        foreach ($this->query as $index => $guid) {
            if ($findLike) {
                if (!preg_match('/' . $value . '/i', $this->dataArray[$guid]->$param)) {
                    unset($this->query[$index]);
                }
            } else {
                if ($this->dataArray[$guid]->$param !== $value) {
                    unset($this->query[$index]);
                };
            }
        }
        array_values($this->query);
        return $this;
    }

    public function add($obj)
    {
        $guid = self::GUID_PREFIX . ++$this->dataTitle->last_guid;
        $this->dataArray[$guid] = $obj;
        $this->query = [$guid];
        return $guid;
    }

    public function delete()
    {
        foreach ($this->query as $guid) {
            unset($this->dataArray[$guid]);
        };
        $this->query = [];
        return true;
    }

    public function first()
    {
        foreach ($this->query as $guid) {
            return $this->query = [$guid];
        }
        return $this;
    }

    public function last()
    {
        $this->query = [end($this->query)];
        return $this;
    }

    public function byGuid($guid)
    {
        if (in_array($guid, $this->query)) {
            $this->query = [$guid];
        } else {
            $this->query = [];
        }
        return $this;
    }

    public function byGuids($guids = [])
    {
        $result = [];
        foreach ($guids as $guid) {
            foreach ($this->query as $query_guid) {
                if ($query_guid = $guid && in_array($guid, $result)) {
                    $result[] = $guid;
                }
            }
        }
        $this->query = $result;
        return $this;
    }

    public function count()
    {
        return count($this->query);
    }


    private function numeric_sort($arr, $param)
    {
        $count = count($arr);
        if ($count <= 1) {
            return $arr;
        }

        $this_guid = $arr[0];
        $this_param_val = $this->dataArray[$this_guid]->$param;
        $left_arr = [];
        $right_arr = [];

        for ($i = 1; $i < $count; $i++) {
            if ($this->dataArray[$arr[$i]]->$param <= $this_param_val) {
                $left_arr[] = $arr[$i];
            } else {
                $right_arr[] = $arr[$i];
            }
        }

        $left_arr = $this->numeric_sort($left_arr, $param);
        $right_arr = $this->numeric_sort($right_arr, $param);

        return array_merge($left_arr, [$this_guid], $right_arr);
    }

    private function string_sort($arr, $param)
    {
        $count = count($arr);
        if ($count <= 1) {
            return $arr;
        }

        $this_guid = $arr[0];
        $this_param_val = $this->dataArray[$this_guid]->$param;
        $left_arr = [];
        $right_arr = [];

        for ($i = 1; $i < $count; $i++) {
            if (strcasecmp($this->dataArray[$arr[$i]]->$param, $this_param_val)) {
                $left_arr[] = $arr[$i];
            } else {
                $right_arr[] = $arr[$i];
            }
        }

        $left_arr = $this->string_sort($left_arr, $param);
        $right_arr = $this->string_sort($right_arr, $param);

        return array_merge($left_arr, [$this_guid], $right_arr);
    }

    public function sortBy($param, $direction_forward = self::SORT_DIRECTION_FORWARD)
    {
        $param_example = $this->dataArray[$this->query[0]]->$param;
        if (is_string($param_example) || is_numeric($param_example)) {
            if (is_string($param_example)) {
                $this->query = $this->numeric_sort($this->query, $param);
            }
            if (is_numeric($param_example)) {
                $this->query = $this->string_sort($this->query, $param);
            }
            if (!$direction_forward){
                $this->query = array_reverse ($this->query);
            }
        }
        return $this;
    }
}