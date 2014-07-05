<?php

namespace DICIT\Config;

class Json extends AbstractConfig
{
    protected $filePath;
    
    public function __construct($file)
    {
        $this->filePath = $file;
    }

    protected function doLoad() {
        return $this->loadFile($this->filePath);
    }
    
    protected function loadFile($filePath) {
        $dirname = dirname($filePath);
        $data = @file_get_contents($filePath);
        $data = $data ? json_decode($data, true) : array();

        if ($data === null) {
            throw new InvalidConfigurationException(sprintf('Invalid JSON configuration file "%s" : %s', $filePath, json_last_error_msg()));
        }
        
        $data = $this->toArray($data);
        
        if (is_array($data) && array_key_exists('include', $data)) {
            foreach($data['include'] as $file) {
                $nestedData = $this->loadFile($dirname . '/' . $file);
                $data = array_merge_recursive($data, $nestedData);
            }
        }
        elseif (is_object($data) && property_exists($data, 'include')) {
            foreach($data->include as $file) {
                $nestedData = $this->loadFile($dirname . '/' . $file);
                $data = array_merge_recursive($data, $nestedData);
            }
        }
        
        return $data;
    }
    
    protected function toArray($obj) {
        if (is_object($obj)) {
            $arr = array(); 
            foreach ($obj as $property => $value) {
                if (is_object($value)) {
                    $value = $this->toArray($value);
                }
                
                $arr[$property] = $value;
            }
            
            return $arr;
        }
        
        return $obj;
    }
}