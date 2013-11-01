<?php
class Berthe_Store_Array {
    protected $data = array();

    public function save($data) {
        $this->data[] = $data;
        return true;
    }
}