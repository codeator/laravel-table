<?php
/**
 * Created by PhpStorm.
 * User: codeator
 * Date: 13.10.16
 * Time: 19:16
 */

namespace Codeator\Table;

use DB;

abstract class Filter
{

    protected $name;
    protected $label;
    protected $theme;
    protected $value;
    protected $viewPath;

    public static function make($type, $name)
    {
        if ($type instanceof Filter) {
            return $type;
        }
        $filterName = 'Codeator\Table\Filter\\' . ucfirst(camel_case($type . 'Filter'));
        $filter = new $filterName($name);

        return $filter;
    }

    public function __construct($name)
    {
        $this->name($name);
        $this->prepare();
    }

    protected abstract function prepare();

    public abstract function applyFilter($model);

    public function name($name)
    {
        $this->name = $name;

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
        if(is_array($this->value)) {
            $value = array_filter($this->value);
            return count($value) ? true : false;
        }

        return $this->value ? true : false;
    }

    public function render()
    {

        return view('table::' . $this->theme . '.' . $this->viewPath, [
            'name'  => $this->name,
            'label' => $this->label,
            'value' => $this->value
        ]);
    }

}