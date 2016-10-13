<?php
/**
 * Created by PhpStorm.
 * User: codeator
 * Date: 13.10.16
 * Time: 13:30
 */

namespace Codeator\Table;

use Request;


class Table
{

    private $columns = [];
    private $sortables = [];
    private $model;
    private $theme;
    private $rows;
    private $pagination;
    private $itemsPerPage = 10;
    private $rowViewPath;
    private $orderField = 'id';
    private $orderDirection = 'asc';

    public static function from($model)
    {
        return new Table($model);
    }

    public function __construct($model = null)
    {
        $this->theme = config('table.theme');
        $this->rowViewPath = 'table::' . $this->theme . '.' . config('table.row');
        $this->model = $model;
    }

    public function columns($columns = [])
    {
        $this->columns = $columns;

        return $this;
    }

    public function sortables($sortables = [])
    {
        $this->sortables = $sortables;

        return $this;
    }

    public function orderBy($field, $direction = 'asc')
    {
        $this->orderField = $field;
        $this->orderDirection = $direction;

        return $this;
    }

    public function paginate($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    protected function prepareModelResults()
    {
        $model = $this->model;
        $model = $this->filterModelResults($model);
        $result = $model->paginate($this->itemsPerPage);
        $this->rows = $result;
        $this->pagination = $result->links();
    }

    protected function filterModelResults($model) {
        $model = $model->orderBy($this->orderField, $this->orderDirection);

        return $model;
    }

    public function row($viewPath)
    {
        $this->rowViewPath = $viewPath;

        return $this;
    }

    protected function setupTable() {
        $this->orderField = Request::input('orderField', $this->orderField);
        $this->orderDirection = Request::input('orderDirection', $this->orderDirection);

        return $this;
    }

    protected function preparedView()
    {
        return view('table::' . $this->theme . '.table', [
            'columns'        => $this->columns,
            'sortables'        => $this->sortables,
            'rows'           => $this->rows,
            'pagination'     => $this->pagination,
            'rowViewPath'    => $this->rowViewPath,
            'orderField'     => $this->orderField,
            'orderDirection' => $this->orderDirection
        ]);
    }

    public function render()
    {
        $this->setupTable();
        $this->prepareModelResults();

        return $this->preparedView()->render();
    }

}