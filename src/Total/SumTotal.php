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
class SumTotal extends Total
{
    public function get($model)
    {
        return $model->select(DB::raw('SUM(' . $this->column . ') as total'))->first()->total;
    }
}