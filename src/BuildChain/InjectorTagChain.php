<?php
/**
 * Created by PhpStorm.
 * User: oliviermadre
 * Date: 02/05/15
 * Time: 15:34
 */

namespace Pyrite\DI\BuildChain;

use Pyrite\DI\Container;

class InjectorTagChain extends AbstractChain
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    protected function canProcess($serviceConfig)
    {
        return array_key_exists('tags.fetch', $serviceConfig);
    }

    protected function doProcess($serviceConfig, $serviceName, $instance)
    {
        if(is_object($instance)) {
            $tagsToFetch = $serviceConfig['tags.fetch'];
            if(is_array($tagsToFetch) && count($tagsToFetch)) {
                if(!is_array(reset($tagsToFetch))) {
                    $tagsToFetch = array($tagsToFetch);
                }
                $this->fetchManyTags($tagsToFetch, $instance);
            }
        }

        if($this->next) {
            return $this->next->process($serviceConfig, $serviceName, $instance);
        }

        return $instance;
    }

    protected function fetchOneTag($tag, $instance)
    {
        list($tagName, $method) = $tag;
        $config = $this->container->getConfig();
        $globalServiceConfig = $config['services'];

        foreach($globalServiceConfig as $sName => $sConfig) {
            if(array_key_exists('tags', $sConfig) && in_array($tagName, $sConfig['tags'])) {
                $instance->$method($this->container->get($sName));
            }
        }
    }

    protected function fetchManyTags(array $tags = array(), $instance)
    {
        foreach($tags as $tag) {
            $this->fetchOneTag($tag, $instance);
        }
    }
}