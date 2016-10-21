<div class="table-filter">
    <form method="get" class="form-vertical">
        @if(count($filters))
            <div class="row">
                @foreach($filters as $filter)
                    {!! $filter->render() !!}
                @endforeach
            </div>
            <input type="hidden" name="orderField" value="{{$orderField}}">
            <input type="hidden" name="orderDirection" value="{{$orderDirection}}">
        @endif
        <div class="row">
            <div class="col-xs-6" style="text-align: left">
                @if(count($filters))
                    <input type="submit" class="btn btn-success" value="Filter">
                    @if($filtersAreActive)
                        <a class="btn btn-warning"
                           href="?orderField={{$orderField}}&orderDirection={{$orderDirection}}">Reset</a>
                    @endif
                @endif
            </div>
            <div class="col-xs-6" style="text-align: right">
                @foreach($exporters as $exporter)
                    <a target="_blank" class="btn btn-info"
                       href="?{{http_build_query(array_merge(\Request::input(), ['export_to' => $exporter]))}}">Export
                        to {{strtoupper($exporter)}}</a>
                @endforeach
            </div>
        </div>
    </form>
</div>

@if (!empty($batchActions))
    <hr>
    <div class="table-batch-actions">
        <form method="get" class="form-vertical" onsubmit="onFormSubmit();">
            <div class="row">
                <div class="form-group">
                    <select name="batch_with" class="form-control">
                        <option value="selected">{{ trans('table::batch.action.selected') }}</option>
                        <option value="all">{{ trans('table::batch.action.all') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="batch" class="form-control" required>
                        <option></option>
                        @foreach ($batchActions as $key => $batchAction)
                            <option value="{{ $key }}">{{ trans('table::batch.labels.'.$key) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <button type="submit" name="batch_action" value="batch" class="btn btn-success">Batch</button>
                </div>
            </div>
        </form>
        <script type="text/javascript">
            function toggleSelectAll() {
                var selectAllCheckbox = window.event.target;
                if (selectAllCheckbox.type != 'checkbox') {
                    selectAllCheckbox = selectAllCheckbox.querySelector('input[type=checkbox]');
                }
                var inputs = document.querySelectorAll(".table-content tbody input[type=checkbox]");
                for (var i = 0; i < inputs.length; i++) {
                    inputs[i].checked = selectAllCheckbox.checked;
                    if (inputs[i].onchange) {
                        inputs[i].onchange();
                    }
                }
            }
            function toggleInnerCheckbox() {
                var target = window.event.target;
                var checkbox = target.querySelector('input[type=checkbox]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    if (checkbox.onchange) {
                        checkbox.onchange();
                    }
                }
            }
            function onFormSubmit() {
                var target = window.event.target;
                var inputs = document.querySelectorAll(".table-content tbody input[type=checkbox]");
                for (var i = 0; i < inputs.length; i++) {
                    if (inputs[i].checked) {
                        var checkbox = inputs[i].cloneNode(true)
                        checkbox.type = 'hidden';
                        target.appendChild(checkbox);
                    }
                }
            }
        </script>
    </div>
@endif
<hr>
<div class="table-content">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            @if (!empty($batchActions))
                <th onclick="toggleInnerCheckbox();">
                    <input type="checkbox" onchange="toggleSelectAll()">
                </th>
            @endif
            @foreach($columns as $key => $column)
                <th>
                    @if(in_array($key, $sortables))
                        @if($orderField == $key)
                            <a href="?{{http_build_query(array_merge(\Request::input(), [ 'orderField' => $key, 'orderDirection' => $orderDirection == 'asc' ? 'desc' : 'asc']))}}">
                                {{$column}}
                                @if($orderDirection == 'asc')
                                    <span class="table-arrow-up"></span>
                                @else
                                    <span class="table-arrow-down"></span>
                                @endif
                            </a>
                        @else
                            <a href="?{{http_build_query(array_merge(\Request::input(), [ 'orderField' => $key, 'orderDirection' => 'asc']))}}">
                                {{$column}}
                                <span class="table-arrow-up"></span><span class="table-arrow-down"></span>&nbsp;
                            </a>
                        @endif
                    @else
                        {{$column}}
                    @endif
                </th>
            @endforeach
            @if(count($actions))
                <th></th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $rowData)
            @include($rowViewPath, [
                'data' => $rowData,
                'columns' => $columns,
                'actions' => $actions,
                'hasBatchActions' => !empty($batchActions)
            ])
        @endforeach
        </tbody>
    </table>
</div>
<div class="table-pagination">
    @include('table::default.pagination', ['paginator' => $pagination])
</div>