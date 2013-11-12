<?php
namespace DICIT\Plugin;

class ParametersPlugin extends AbstractPlugin {
    public function invoke($args) {
        $config = $this->getConfig();
        if (array_key_exists("parameters", $config)) {
            return $this->fetchKey($args, $config['parameters']);
        }
        else {
            return null;
        }
    }

    protected function fetchKey($key, array $arrayContext = array()) {
        $key = $this->translateKey($key, $arrayContext);

        $explode = explode (".", $key);

        $currentNode = $arrayContext;
        $nbArgs = count($explode);
        $currentLevel = 0;
        foreach($explode as $key) {
            $currentLevel++;

            if ($currentLevel === $nbArgs) {
                if (is_scalar($currentNode[$key])) {
                    return $currentNode[$key];
                }
                else {
                    return null;
                }
            }
            else {
                if (is_array($currentNode)) {
                    if (array_key_exists($key, $currentNode)) {
                        $currentNode = $currentNode[$key];
                    }
                    else {
                        return null;
                    }
                }
            }
        }

        return null;
    }

    protected function translateKey($key, array $arrayContext = array()) {
        $finalTranslatedKey = array();

        $explode = explode(".", $key);
        foreach($explode as $keyPart) {
            if (substr($keyPart, 0, 1) === "%") {
                $finalTranslatedKey[] = $this->fetchKey(substr($keyPart, 1), $arrayContext);
            }
            else {
                $finalTranslatedKey[] = $keyPart;
            }
        }

        return implode('.', $finalTranslatedKey);
    }
}