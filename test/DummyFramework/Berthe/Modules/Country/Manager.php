<?php
class Berthe_Modules_Country_Manager {
    /**
     * @var Berthe_Store_Abstract
     */
    public $storage = null;

    public function __construct() {

    }

    protected function validate() {
        
        return true;
    }

    public function save($data) {
        $this->validate();
        return $this->storage->save($data);
    }
}