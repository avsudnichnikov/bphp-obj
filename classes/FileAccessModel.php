<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 19.04.2019
 * Time: 14:52
 */

class FileAccessModel
{
    protected $fileName;
    protected $file;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    private function connect()
    {
        $this->file = fopen($this->fileName, 'r+');
    }

    private function disconnect()
    {
        fclose($this->file);
    }

    public function read()
    {
        $this->connect();
        $length = filesize($this->fileName);
        $data = fread($this->file, $length);
        $this->disconnect();
        return $data;
    }

    public function write($data)
    {
        $result = false;
        $this->connect();
        if(fwrite($this->file, $data)){
           $result = true;
        };
        $this->disconnect();
        return $result;
    }

}