<?php

namespace Codeator\Table\Filter;

use Codeator\Table\Filter;
use Request, DB;

/**
 * Created by PhpStorm.
 * User: codeator
 * Date: 13.10.16
 * Time: 19:16
 */
class DateFilter extends Filter
{

    protected $viewPath = 'filters.date';

    protected function prepare()
    {
        $value = Request::input('f_' . $this->name);
        $this->value['from'] = array_get($value, 'from');
        $this->value['to'] = array_get($value, 'to');
    }

    public function applyFilter($model)
    {
        if ($from = array_get($this->value, 'from')) {
            $model = $model->where(DB::raw('DATE_FORMAT('.$model->getModel()->getTable().'.'.$this->name.', "%Y-%m-%d")'), '>=', date('Y-m-d', strtotime($from)));
        }

        if ($to = array_get($this->value, 'to')) {
            $model = $model->where(DB::raw('DATE_FORMAT('.$model->getModel()->getTable().'.'.$this->name.', "%Y-%m-%d")'), '<=', date('Y-m-d', strtotime($to)));
        }

        return $model;
    }
}