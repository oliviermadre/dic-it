<?php

namespace DICIT\Config;

class ArrayConfig extends AbstractConfig
{
    /**
     * ArrayConfig constructor.
     *
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $this->data = $configs;
    }

    protected function doLoad() {}
}

