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
    private $filters = [];
    private $preparedFilters = [];
    private $filterCallback = null;
    private $exporters = [];
    private $batchActions = [];
    private $model;
    private $theme;
    private $rows;
    private $pagination;
    private $itemsPerPage = 10;
    private $rowViewPath;
    private $orderField = 'id';
    private $orderDirection = 'asc';
    private $filtersAreActive = false;
    private $actions = [];
    private $totals = [];
    private $preparedTotals = [];

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

    public function filters($filters = [])
    {
        $this->filters = $filters;

        return $this;
    }

    public function filterCallback($callback)
    {
        $this->filterCallback = $callback;

        return $this;
    }

    public function exporters($exporters = [])
    {
        $this->exporters = $exporters;

        return $this;
    }

    public function actions($actions = [])
    {
        $this->actions = $actions;

        return $this;
    }

    public function totals($totals = [])
    {
        $this->totals = $totals;

        return $this;
    }

    public function batchActions($batchActions = [])
    {
        $this->batchActions = $batchActions;

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
        $this->filterModelResults($this->model);
        $this->sortModelResults($this->model);
        $this->prepareExporters();
        $this->runBatch();
        $this->prepareTotals($this->model);

        $result = $this->model->paginate($this->itemsPerPage);
        $this->rows = $result;
        $this->pagination = $result->appends(\Request::input());
    }

    protected function prepareExporters()
    {
        if ($exporterType = \Request::input('export_to')) {
            $exporter = Exporter::make($exporterType, array_keys($this->columns));
            $exporter->export($this->model);
        }
    }

    protected function prepareModelFilters()
    {
        $filters = [];
        foreach ($this->filters as $name => $type) {
            $filter = Filter::make($type, $name);
            $filter->label(array_get($this->columns, $name))
                ->theme($this->theme);

            $filters[] = $filter;
        }

        $this->preparedFilters = $filters;

        return $filters;
    }

    protected function prepareTotals()
    {
        $totals = [];
        foreach ($this->totals as $name => $type) {
            $total = Total::make($type, $name);
            $totals[$name] = [
                'total' => $total->get($this->model),
                'type' => $type
            ];
        }

        $this->preparedTotals = $totals;

        return $totals;
    }

    protected function prepareQuery()
    {
        $filters = [];
        foreach ($this->filters as $name => $type) {
            $filter = Filter::make($type, $name);
            $filter->label(array_get($this->columns, $name))
                ->theme($this->theme);

            $filters[] = $filter;
        }

        $this->preparedFilters = $filters;

        return $filters;
    }

    protected function runBatch()
    {
        if (\Request::has('batch_action')) {
            if ($action = array_get($this->batchActions, \Request::get('batch'))) {
                $query = clone $this->model;
                if (\Request::has('batch_with')) {
                    $ids = \Request::get('b');
                    $query->whereIn('id', $ids);
                }
                $action($query);

                return redirect()->back()->send();
            }
        }

        return $this;
    }

    protected function filterModelResults($model)
    {
        $this->prepareModelFilters();
        foreach ($this->preparedFilters as $filter) {
            $this->model = $filter->applyFilter($this->model);
            if ($filter->isActive()) {
                $this->filtersAreActive = true;
            }
        }
        if (is_callable($callback = $this->filterCallback)) {
            $this->model = call_user_func($callback, $this->model);
        }
    }

    protected function sortModelResults($model)
    {
        $model = $model->orderBy($this->orderField, $this->orderDirection);

        return $model;
    }

    public function row($viewPath)
    {
        $this->rowViewPath = $viewPath;

        return $this;
    }

    protected function setupTable()
    {
        $this->orderField = Request::input('orderField', $this->orderField);
        $this->orderDirection = Request::input('orderDirection', $this->orderDirection);

        return $this;
    }

    protected function preparedView()
    {
        return view('table::' . $this->theme . '.table', [
            'columns'          => $this->columns,
            'sortables'        => $this->sortables,
            'rows'             => $this->rows,
            'pagination'       => $this->pagination,
            'rowViewPath'      => $this->rowViewPath,
            'orderField'       => $this->orderField,
            'orderDirection'   => $this->orderDirection,
            'filters'          => $this->preparedFilters,
            'filtersAreActive' => $this->filtersAreActive,
            'exporters'        => $this->exporters,
            'actions'          => $this->actions,
            'batchActions'     => $this->batchActions,
            'totals'           => $this->preparedTotals
        ]);
    }

    public function render()
    {
        $this->setupTable();
        $this->prepareModelResults();

        return $this->preparedView()->render();
    }

}