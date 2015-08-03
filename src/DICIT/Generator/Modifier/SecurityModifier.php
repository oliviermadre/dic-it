<?php

namespace DICIT\Generator\Modifier;

use DICIT\Container;
use DICIT\Util\ParamsResolver;
use Trolamine\Core\Access\OperationConfigAttribute;
use Trolamine\Core\Operation\MethodSecurityExpressionRoot;

class SecurityModifier implements Modifier
{
    public function canModify(array $serviceConfig = array())
    {
        return array_key_exists('security', $serviceConfig);
    }

    public function modify($serviceName, array $serviceConfig = array())
    {
        $call = "\\" . get_called_class() . "::" . "resolveModifier";

        $configString = var_export($serviceConfig, true);

        $code = <<<PHP
\$applySecurization = function(&\$instance) use(\$container) {
    \$array = $configString;
    \$securityConfig = $call(\$container, \$array);
    \$instance = \$container->get('SecuredClassFactory')->build(\$instance, '$serviceName', \$securityConfig);
};

\$applySecurization(\$instance);


PHP;
        return $code;
    }

    public static function resolveModifier(Container $container, array &$serviceConfig)
    {
        $securityConfig = ParamsResolver::resolveParams($container, $serviceConfig['security']);
        $realSecurityConfig = array();

        foreach ($securityConfig as $method => $triggers) {
            $newTriggers = array();
            foreach ($triggers as $triggerName => $functions) {
                $operations = array();
                foreach ($functions as $alias => $params) {
                    if (array_key_exists('method', $params)) {
                        $realParams = ParamsResolver::resolveParams($container, $params);

                        $root = array_key_exists('operation', $realParams) ? $realParams['operation'] : new MethodSecurityExpressionRoot();
                        $args  = array_key_exists('args', $realParams) ? $realParams['args'] : array();

                        $operations[] = new OperationConfigAttribute($root, $realParams['method'], $args);
                    }
                }
                $newTriggers[$triggerName] = $operations;
            }
            $realSecurityConfig[$method] = $newTriggers;
        }

        return $realSecurityConfig;
    }
}