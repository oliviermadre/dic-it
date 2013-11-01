<?php
class DICIT_ConfigYML extends DICIT_ConfigAbstract {
    protected $filePath = null;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function load() {
        return $this->loadFile($this->filePath);
    }

    protected function loadFile($filePath) {
        $yml = array();
        $dirname = dirname($this->filePath);
        $yaml = new Symfony\Component\Yaml\Yaml();
        $res = $yaml->parse($this->filePath);
        if (array_key_exists('include', $res)) {
            foreach($res['include'] as $key => $value) {
                $yml = array_merge_recursive($yml, $yaml->parse($dirname . '/'. $value));
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