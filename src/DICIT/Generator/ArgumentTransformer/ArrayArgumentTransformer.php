<?php

namespace DICIT\Generator\ArgumentTransformer;

use DICIT\Generator\ArgumentTransformerFactory;

class ArrayArgumentTransformer implements ArgumentTransformer
{
    const SALT = 'This is a cool salt that will help us out having no conflict in placeholder creation';

    /**
     * @var ArgumentTransformerFactory
     */
    protected $factory;

    public function __construct(ArgumentTransformerFactory $factory)
    {
        $this->factory = $factory;
    }

    public function canTransform($argument)
    {
        return is_array($argument);
    }

    public function transform($argument)
    {
        $arrayWithPlaceholders = $argument;

        $placeholders = array();
        $values = array();
        array_walk_recursive($arrayWithPlaceholders, function (&$value, $key) use (&$placeholders, &$values) {
            $placeholders[] = md5(ArrayArgumentTransformer::SALT . md5($value));
            $values[] = $value;

            $value = end($placeholders);
        });

        $outputString = var_export($arrayWithPlaceholders, true);
        foreach ($placeholders as $key => $placeholder) {
            list($transformedValue, ) = $this->factory->transformOne($values[$key]);
            $outputString = str_replace("'" . $placeholder . "'", $transformedValue, $outputString);
        }
        return $outputString;
    }

    public function isPHPCode()
    {
        return true;
    }
}
