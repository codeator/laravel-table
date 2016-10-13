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
class RangeFilter extends Filter
{

    protected $viewPath = 'filters.range';

    protected function prepare() {
        $value = Request::input('f_'.$this->name);
        $this->value['from'] = array_get($value, 'from');
        $this->value['to'] = array_get($value, 'to');
    }

    public function applyFilter($model) {

        if($from = array_get($this->value, 'from')) {
            $model = $model->where($this->name, '>=', $from);
        }

        if($to = array_get($this->value, 'to')) {
            $model = $model->where($this->name, '<=', $to);
        }

        return $model;
    }

}