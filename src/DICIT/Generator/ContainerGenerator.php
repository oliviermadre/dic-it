<?php

namespace DICIT\Generator;

use DICIT\Config\AbstractConfig;

interface ContainerGenerator
{

    /**
     * @param AbstractConfig $config
     * @param string|null $generatedNamespace
     * @param string|null $generatedClassname
     * @return string
     */
    public function generate(AbstractConfig $config, $generatedNamespace = null, $generatedClassname = null);
}
