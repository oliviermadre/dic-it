<?php
class Berthe_Service_Country {
    protected $manager = null;
    protected $int = 0;

    public function __construct($manager, $int) {
        $this->manager = $manager;
        $this->int = $int;
    }

    public function setManager($manager) {
        $this->manager = $manager;
    }
}

class Berthe_Modules_Country_Manager {
    public $storage;

    public function __construct() {

    }

}
class Berthe_Store_Echo {
    public $injectedVariable = null;

    public function __construct() {}

}
