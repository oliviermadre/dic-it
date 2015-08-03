<?php

namespace DICIT\Generator;

use DICIT\Config\AbstractConfig;
use RuntimeException;

class GeneratedContainerRetriever
{
    protected $wrapped;

    public function __construct(ContainerGenerator $wrapped)
    {
        $this->wrapped = $wrapped;
    }

    /**
     * @param AbstractConfig $config
     * @param string $generatedNamespace
     * @param string $generatedClassname
     * @return string
     */
    public function get($config, $generatedNamespace, $generatedClassname, $destinationPath)
    {
        $sanitizedNS = $this->sanitizeNamespace($generatedNamespace);
        $sanitizedCN = $this->sanitizeClassname($generatedClassname);
        $sanitizedDir = $this->sanitizeDestinationPath($destinationPath);

        if ($this->mustRegenerate($config, $sanitizedDir)) {
            $this->regenerate($config, $sanitizedNS, $sanitizedCN, $sanitizedDir);
        }

        return $this->load($config, $sanitizedNS, $sanitizedCN, $sanitizedDir);
    }

    /**
     * @param $string
     * @return string
     */
    private function sanitizeNamespace($string)
    {
        return trim($string, '/\\');
    }

    /**
     * @param $string
     * @return mixed
     */
    private function sanitizeClassname($string)
    {
        return str_replace('\\', '', $string);
    }

    /**
     * @param $destinationPath
     * @return string
     */
    private function sanitizeDestinationPath($destinationPath)
    {
        if (!is_dir($destinationPath)) {
            throw new \RuntimeException(sprintf("Destination path is not a valid directory, '%s' given", $destinationPath));
        }

        return rtrim($destinationPath, DIRECTORY_SEPARATOR);
    }

    /**
     * @param $config
     * @param $dir
     * @return bool
     */
    private function mustRegenerate($config, $dir)
    {
        $hash = $this->getConfigHash($config);
        $containerPath = $this->getContainerPath($dir, $hash);

        if (file_exists($containerPath)) {
            return false;
        }

        return true;
    }

    /**
     * @param $config
     * @param $ns
     * @param $cn
     * @param $dir
     */
    private function regenerate($config, $ns, $cn, $dir)
    {
        $code = $this->wrapped->generate($config, $ns, $cn);
        file_put_contents($this->getContainerPath($dir, $this->getConfigHash($config)), $code);
    }

    /**
     * @param $dir
     * @param $hash
     * @return string
     */
    private function getContainerPath($dir, $hash)
    {
        return $dir . DIRECTORY_SEPARATOR . $hash . '.php';
    }

    /**
     * @param $config
     * @return string
     */
    private function getConfigHash($config)
    {
        $phpStringConfig = $config->load();
        $hash = md5(var_export($phpStringConfig, true));

        return $hash;
    }

    /**
     * @param $config
     * @param $sanitizedNS
     * @param $sanitizedCN
     * @param $sanitizedDir
     * @return mixed
     */
    private function load($config, $sanitizedNS, $sanitizedCN, $sanitizedDir)
    {
        $containerPath = $this->getContainerPath($sanitizedDir, $this->getConfigHash($config));
        require_once $containerPath;

        $fqcn = '\\' . $sanitizedNS . '\\' . $sanitizedCN;
        if (class_exists($fqcn)) {
            return new $fqcn();
        }

        throw new RuntimeException(
            sprintf("Couldn't instanciate %s using cache path \"%s\"", $fqcn, $containerPath)
        );
    }
}