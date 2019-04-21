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

    const GET_WITH_GUID = true;
    const GET_WITHOUT_GUID = false;

    const PARAM_TYPE_NULL = 0;
    const PARAM_TYPE_NUMERIC = 1;
    const PARAM_TYPE_STRING = 2;
    const PARAM_TYPE_UNSORTED = 3;

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

    public function get($withGuid = self::GET_WITHOUT_GUID)
    {
        $result = [];
        if ($withGuid) {
            foreach ($this->query as $guid) {
                $result[$guid] = $this->dataArray[$guid];
            }
        } else {
            $counter = 0;
            foreach ($this->query as $guid) {
                $result[$counter++] = $this->dataArray[$guid];
            }
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

    public function changeParam($param, $new_value)
    {
        foreach ($this->query as $obj) {
            $obj->$param = $new_value;
        }
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


    private function numericSort($arr, $param)
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
            if (
                (($this_param_val === null) ||
                    ($this->dataArray[$arr[$i]]->$param === null)
                ) xor
                ($this->dataArray[$arr[$i]]->$param <= $this_param_val)
            ) {
                $left_arr[] = $arr[$i];
            } else {
                $right_arr[] = $arr[$i];
            }
        }

        $left_arr = $this->numericSort($left_arr, $param);
        $right_arr = $this->numericSort($right_arr, $param);

        return array_merge($left_arr, [$this_guid], $right_arr);
    }

    private function stringSort($arr, $param)
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
            if (
                (
                    ($this_param_val === null) ||
                    ($this->dataArray[$arr[$i]]->$param === null
                )
            ) xor
                strcasecmp($this->dataArray[$arr[$i]]->$param, $this_param_val) > 0
            ) {
                $right_arr[] = $arr[$i];
            } else {
                $left_arr[] = $arr[$i];
            }
        }

        $left_arr = $this->stringSort($left_arr, $param);
        $right_arr = $this->stringSort($right_arr, $param);

        return array_merge($left_arr, [$this_guid], $right_arr);
    }

    private function identifyParamType($param, $iteration = 0, $max = null)
    {
        $paramExample = $this->dataArray[$this->query[$iteration]]->$param;
        if (is_null($max)) {
            $max = $this->count();
        }
        if (is_null($paramExample)) {
            if (($iteration + 1) < $max) {
                return $this->identifyParamType($param, $iteration + 1, $max);
            } else return self::PARAM_TYPE_NULL;
        }
        if (is_numeric($paramExample)) {
            return self::PARAM_TYPE_NUMERIC;
        }
        if (is_string($paramExample)) {
            return self::PARAM_TYPE_STRING;
        }
        return self::PARAM_TYPE_UNSORTED;
    }

    public function orderBy($param, $direction_forward = self::SORT_DIRECTION_FORWARD)
    {
        $param_type = $this->identifyParamType($param);
        if ($param_type === self::PARAM_TYPE_NUMERIC || $param_type === self::PARAM_TYPE_STRING) {
            if ($param_type === self::PARAM_TYPE_NUMERIC) {
                $this->query = $this->numericSort($this->query, $param);
            };
            if ($param_type === self::PARAM_TYPE_STRING) {
                $this->query = $this->stringSort($this->query, $param);
            };
            if (!$direction_forward) {
                $this->query = array_reverse($this->query);
            }
        }
        return $this;
    }

    public
    function limit($limit)
    {
        $this->query = array_slice($this->query, 0, $limit);
        return $this;
    }
}