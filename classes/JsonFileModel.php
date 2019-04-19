<?php
/**
 * Author: avsudnichnikov (alsdew@ya.ru)
 * Date: 17.04.2019
 * Time: 18:21
 */

class JsonFileModel
{
    public $fileName;
    public $file;

    public function __construct($dataModelName)
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/settings.php';
        $this->fileName = $settings['database_path'] . $dataModelName . 's.json';
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
        $data = json_decode(fread($this->file, $length));
        $this->disconnect();
        return $data;
    }

    public function write($data)
    {
        $this->connect();
        fwrite($this->file, $data);
        $this->disconnect();
    }

    public function writeObjArr($array = [])
    {
        $text = "{\r\n";
        foreach ($array as $name => $newStroke) {
            $text .= '"' . $name . '": ' . json_encode($newStroke, JSON_PRETTY_PRINT) . ",\r\n";
        }
        $text = substr($text, 0, -3);
        $text .= "\r\n}";
        $this->write($text);
    }

}