<?php
class DICIT_ConfigPHP extends DICIT_ConfigAbstract {
    protected $filePath = null;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function load() {
        if (file_exists($this->filePath) && is_readable($this->filePath)) {
            include $this->filePath;
            return $array;
        }
        else {
            throw new RuntimeException("Couldn't load the array in path '" . $this->filePath . "'");
        }
    }
}