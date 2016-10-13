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

    protected function prepare()
    {
        $this->value = Request::input('f_' . $this->name);
    }

    public function applyFilter($model)
    {
        $model = $model->where($this->name, 'like', '%' . $this->value . '%');
        return $model;
    }

}