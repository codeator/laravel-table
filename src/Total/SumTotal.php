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
    protected $multiplier = 1;

    public function params($params) {
        if (($multiplier = array_get($params, 'multiplier')) !== null) {
            $this->multiplier = $multiplier;
        }

        return parent::params($params);
    }

    public function get($model)
    {
        return $model->select(DB::raw('SUM(' . $model->getModel()->getTable().'.'.$this->column . (($this->multiplier && $this->multiplier != 1 ) ? ' / '.$this->multiplier : '').') as total'))->first()->total;
    }
}