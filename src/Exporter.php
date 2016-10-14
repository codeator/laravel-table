<?php
/**
 * Created by PhpStorm.
 * User: codeator
 * Date: 13.10.16
 * Time: 19:16
 */

namespace Codeator\Table;

class Exporter
{
    protected $columns = [];

    public static function make($type, $columns)
    {
        $exporterName = 'Codeator\Table\Exporter\\' . ucfirst(camel_case($type . 'Exporter'));
        $exporter = new $exporterName();
        $exporter->columns($columns);

        return $exporter;
    }

    public function columns($columns = []) {
        $this->columns = $columns;

        return $this;
    }

}