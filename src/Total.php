<?php
/**
 * Created by PhpStorm.
 * User: codeator
 * Date: 13.10.16
 * Time: 19:16
 */

namespace Codeator\Table;

class Total
{
    protected $column = null;
    protected $params;
    protected $type;

    public static function make($type, $name)
    {
        if ($type instanceof Filter) {
            return $type;
        }
        $params = [];
        if (str_contains($type, '|')) {
            list ($type, $paramString) = explode('|', $type, 2);
            $paramPairs = explode('|', $paramString);
            foreach ($paramPairs as $param) {
                list($key, $valueString) = explode(':', $param);
                $params[$key] = str_contains($valueString, ',') ? explode(',', $valueString) : $valueString;
            }
        }

        $className = 'Codeator\Table\Total\\' . ucfirst(camel_case($type . 'Total'));

        $filter = self::create($name, $params, $className);
        $filter->type($type);

        return $filter;
    }

    protected static function create($column, $params, $className)
    {
        $reflectionClass = new \ReflectionClass($className);
        $preparedParams = [
            'column' => $column,
            'params' => $params,
        ];

        return $reflectionClass->newInstanceArgs($preparedParams);
    }

    protected static function exportParameterValue($params, \ReflectionParameter $parameter)
    {
        if ($value = array_get($params, $parameter->getName())) {
            return $value;
        }
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
        $declaringClass = $parameter->getDeclaringClass();
        if ($declaringClass) {
            throw new \InvalidArgumentException(sprintf("Argument \"%s\" for filter \"%s\" is required.", $parameter->getName(), $declaringClass->getName()));
        } else {
            throw new \InvalidArgumentException(sprintf("Argument \"%s\" is required.", $parameter->getName()));
        }
    }

    public function __construct($column, $params = [])
    {
        $this->column($column);
        if (array_has($params, 'label')) {
            $this->label(array_get($params, 'label'));
        }
        $this->params($params);
    }

    public function column($column)
    {
        $this->column = $column;

        return $this;
    }

    public function params($params)
    {
        $this->params = $params;

        return $this;
    }

    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

}