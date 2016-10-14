<?php

namespace Codeator\Table\Exporter;
use Codeator\Table\Exporter;

/**
 * Created by PhpStorm.
 * User: codeator
 * Date: 14.10.16
 * Time: 11:43
 */
class CsvExporter extends Exporter 
{

    public function export($model) {
        $results = $model->get($this->columns)->toArray();

        $csv = $this->toCSV($results);

        header('Content-Disposition: attachment; filename="export.csv"');
        header("Cache-control: private");
        header("Content-type: application/force-download");
        header("Content-transfer-encoding: binary\n");

        echo $csv;

        exit;
    }

    function toCSV( array $fields, $delimiter = ';', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false ) {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');

        $outputString = "";
        foreach($fields as $tempFields) {
            $output = array();
            foreach ( $tempFields as $field ) {
                if ($field === null && $nullToMysqlNull) {
                    $output[] = 'NULL';
                    continue;
                }

                // Enclose fields containing $delimiter, $enclosure or whitespace
                if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
                    $field = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
                }
                $output[] = $field." ";
            }
            $outputString .= implode( $delimiter, $output )."\r\n";
        }
        return $outputString; }

}