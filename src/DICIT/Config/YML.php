<?php
namespace DICIT\Config;

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
                    $yml = array_merge_recursive($yml, $subYml);
                }
            }
            else {
                $yml = array_merge_recursive($yml, array($key => $res[$key]));
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
