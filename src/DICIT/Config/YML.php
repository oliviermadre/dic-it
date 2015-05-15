<?php
namespace DICIT\Config;

use DICIT\Util\Arrays;

class YML extends AbstractConfig
{
    protected $filePath = null;
    protected $data = array();

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    protected function doLoad()
    {
        return $this->loadFile($this->filePath);
    }

    protected function loadFile($filePath)
    {
        if (!is_file($filePath)) {
            throw new \Exception('File not found: ' . $filePath);
        }
        $yml = array();
        $dirname = dirname($filePath);
        $yaml = new \Symfony\Component\Yaml\Yaml();
        $res = $yaml->parse(file_get_contents($filePath));

        foreach ($res as $key => $value) {
            if ($key == 'include') {
                foreach ($value as $file) {
                    $file = preg_replace_callback('`\${env\.([^}]+)}`i', function ($matches) { return getenv($matches[1]); }, $file);
                    $subYml = $this->loadFile($dirname . '/' . $file);
                    $yml = Arrays::mergeRecursiveUnique($yml, $subYml);
                }
            } else {
                $yml = Arrays::mergeRecursiveUnique($yml, array($key => $res[$key]));
            }
        }

        return $yml;
    }

    public function compile()
    {
        $ret = $this->load();
        $dump = var_export($ret, true);
        return $dump;
    }
}
