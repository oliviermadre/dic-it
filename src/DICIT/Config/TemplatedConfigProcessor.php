<?php

namespace DICIT\Config;

use DICIT\ArrayResolver;

class TemplatedConfigProcessor
{
    
    public static function process(ArrayResolver $config)
    {
        $instances = $config->resolve('apply-templates', array());
        
        foreach ($instances as $name => $instance) {
            $templateName = $instance->resolve('template', null);
            
            if (! $templateName) {
                throw new \RuntimeException("Template name not declared in '$name'.");
            }
            
            $template = $config->resolve("templates.$templateName", null);
            
            if (! $template) {
                throw new \RuntimeException("Template '$template' not declared.");
            }
            
            $templateText = json_encode($template->extract());
            $variables = $instance->resolve('apply', array());
            
            foreach ($variables as $variable => $value) {
                $templateText = str_replace(sprintf('{{%s}}', $variable), $value, $templateText);    
            }
            
            $config['classes'][$name] = json_decode($templateText, true);
        }
        
        return $config;
    }
    
}