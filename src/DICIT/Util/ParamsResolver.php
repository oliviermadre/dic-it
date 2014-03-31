<?php
namespace DICIT\Util;

use DICIT\Container;

class ParamsResolver
{
    /**
     * Resolve the params of an array
     * 
     * @param array $params
     * @return array
     */
    public static function resolveParams(Container $container, $params) {
        $resolvedParams = array();
        foreach($params as $key=>$param) {
            $resolvedParam = null;
            if (is_array($param)) {
                $resolvedParam = self::resolveParams($container, $param);
            } else if (is_string($param)){
                $resolvedParam = $container->resolve($param);
            } else {
                $resolvedParam = $param;
            }
            $resolvedParams[$key] = $resolvedParam;
        }
        return $resolvedParams;
    }
}