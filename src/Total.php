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

    public static function make($type, $column)
    {
        $className = 'Codeator\Table\Total\\' . ucfirst(camel_case($type . 'Total'));
        $class = new $className();
        $class->column($column);

        return $class;
    }

    public function column($column) {
        $this->column = $column;

        return $this;
    }

}