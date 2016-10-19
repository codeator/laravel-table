<?php
/**
 * Created by PhpStorm.
 * User: codeator
 * Date: 13.10.16
 * Time: 19:16
 */

namespace Codeator\Table;

abstract class Filter
{

    protected $name;
    protected $params;
    protected $label;
    protected $theme;
    protected $value;
    protected $viewPath;

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

        $className = 'Codeator\Table\Filter\\' . ucfirst(camel_case($type . 'Filter'));

        $filter = self::createFilter($name, $params, $className);

        return $filter;
    }

    protected static function createFilter($name, $params, $className)
    {
        $reflectionClass = new \ReflectionClass($className);
        $preparedParams = [
            'name' => $name,
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

    public function __construct($name, $params = [])
    {
        $this->name($name);
        if (array_has($params, 'label')) {
            $this->label(array_get($params, 'label'));
        }
        $this->params($params);
        $this->prepare();
    }

    protected abstract function prepare();

    public abstract function applyFilter($model);

    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    public function params($params)
    {
        $this->params = $params;

        return $this;
    }

    public function label($label)
    {
        if ($label) {
            $this->label = $label;
        }

        return $this;
    }

    public function theme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    public function isActive()
    {
        if (is_array($this->value)) {
            $value = array_filter($this->value);
            return count($value) ? true : false;
        }

        return $this->value ? true : false;
    }

    public function render()
    {

        return view('table::' . $this->theme . '.' . $this->viewPath, [
            'name' => $this->name,
            'label' => $this->label,
            'value' => $this->value
        ]);
    }

}