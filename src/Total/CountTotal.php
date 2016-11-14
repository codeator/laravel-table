<?php

namespace Codeator\Table\Total;

use Codeator\Table\Total;
use DB;

/**
 * Created by PhpStorm.
 * User: codeator
 * Date: 11.11.16
 * Time: 13:23
 */
class CountTotal extends Total
{
    public function get($model)
    {
        return $model->select(DB::raw('COUNT(' . $model->getModel()->getTable().'.'.$this->column . ') as total'))->first()->total;
    }
}