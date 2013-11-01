<?php
class Berthe_Store_Echo {
    public $injectedVariable = null;

    public function save($data) {
        echo "Berthe_Store_Echo : " . $data . " " . $this->injectedVariable . "\n";
        return true;
    }
}