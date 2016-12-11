<?php

namespace Codeator\Table\Filter;

use Codeator\Table\Filter;

use Request;

/**
 * Created by PhpStorm.
 * User: codeator
 * Date: 13.10.16
 * Time: 19:16
 */
class StringFilter extends Filter
{

    protected $viewPath = 'filters.string';
    protected $isStrict = false;

    public function params($params)
    {
        if (($isStrict = array_get($params, 'strict')))
        {
            $this->isStrict = $isStrict;
        }

        return parent::params($params);
    }

    protected function prepare()
    {
        $this->value = Request::input('f_' . $this->name);
    }

    public function applyFilter($model)
    {
        if ($this->value)
        {
            if ($this->isStrict)
            {
                $model = $model->where($model->getModel()->getTable() . '.' . $this->name, '=', $this->value);
            }
            else
            {
                $model = $model->where($model->getModel()->getTable() . '.' . $this->name, 'like', '%' . $this->value . '%');
            }
        }

        return $model;
    }

}