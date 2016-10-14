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

    public static function make($type)
    {
        $exporterName = 'Codeator\Table\Exporter\\' . ucfirst(camel_case($type . 'Exporter'));
        $exporter = new $exporterName();

        return $exporter;
    }

}