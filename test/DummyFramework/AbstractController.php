<?php
class AbstractController {
    protected $container = null;

    public function setContainer($container) {
        $this->container = $container;
        return $this;
    }
}