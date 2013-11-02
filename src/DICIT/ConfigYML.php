<?php
class DICIT_ConfigYML extends DICIT_ConfigAbstract {
    protected $filePath = null;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function load() {
        $ret = $this->loadFile($this->filePath);
        var_dump($ret);
        return $ret;
    }

    protected function loadFile($filePath) {
        $yml = array();
        $dirname = dirname($filePath);
        $yaml = new Symfony\Component\Yaml\Yaml();
        $res = $yaml->parse($filePath);

        foreach($res as $key => $value) {
            if ($key == 'include') {
                foreach($value as $file) {
                    $subYml = $this->loadFile($dirname . '/' . $file);
                    $yml = array_merge_recursive($yml, $subYml);
                }
            }
            elseif ($key == 'classes') {
                $yml = array_merge_recursive($yml, array('classes' => $res[$key]));
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