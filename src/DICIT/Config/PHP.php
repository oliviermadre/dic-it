<?php
namespace DICIT\Config;

class PHP extends AbstractConfig
{
    protected $filePath = null;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    protected function doLoad() {
        if (file_exists($this->filePath) && is_readable($this->filePath)) {
            include $this->filePath;
            return $array;
        }
        else {
            throw new \RuntimeException("Couldn't load the array in path '" . $this->filePath . "'");
        }
    }
}
