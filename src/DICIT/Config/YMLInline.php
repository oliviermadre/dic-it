<?php
namespace DICIT\Config;

use Symfony\Component\Yaml\Yaml;

class YMLInline extends AbstractConfig
{
    protected $inline = '';
    protected $data = array();

    public function __construct($string)
    {
        $this->inline = $string;
    }

    protected function doLoad()
    {
        return $this->loadInline($this->inline);
    }

    protected function loadInline($inline)
    {
        $yaml = new Yaml();
        $res = $yaml->parse($inline);
        return $res;
    }

    public function compile()
    {
        $ret = $this->load();
        $dump = var_export($ret, true);
        return $dump;
    }
}
