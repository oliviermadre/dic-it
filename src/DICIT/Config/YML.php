<?php
namespace DICIT\Config;

use DICIT\Util\Arrays;

class YML extends AbstractConfig
{
    protected $filePath = null;
    protected $data = array();

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    protected function doLoad() {
        return $this->loadFile($this->filePath);
    }

    protected function loadFile($filePath) {
        $yml = array();
        $dirname = dirname($filePath);
        $yaml = new \Symfony\Component\Yaml\Yaml();
        $res = $yaml->parse($filePath);

        foreach($res as $key => $value) {
            if ($key == 'include') {
                foreach($value as $file) {
                    $subYml = $this->loadFile($dirname . '/' . $file);
                    $yml = Arrays::merge_recursive_unique($yml, $subYml);
                }
            }
            else {
                $yml = Arrays::merge_recursive_unique($yml, array($key => $res[$key]));
            }
        }

        return $yml;
    }

    public function compile() {
        $ret = $this->load();
        $dump = var_export($ret, true);
        return $dump;
    }
}
