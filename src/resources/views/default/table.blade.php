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
                            <a class="btn btn-warning" href="?orderField={{$orderField}}&orderDirection={{$orderDirection}}">Reset</a>
                        @endif
                    @endif
                </div>
                {{--<div class="col-xs-6" style="text-align: right">--}}
                    {{--<a class="btn btn-info" href="">Export to CSV</a>--}}
                {{--</div>--}}
            </div>
        </form>
</div>
<div class="table-content">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            @foreach($columns as $key => $column)
                <th>
                    @if(in_array($key, $sortables))
                        @if($orderField == $key)
                            <a href="?{{http_build_query(array_merge(\Request::input(), [ 'orderField' => $key, 'orderDirection' => $orderDirection == 'asc' ? 'desc' : 'asc']))}}">
                                {{$column}}
                                @if($orderDirection == 'asc')
                                &uarr;
                                &nbsp;
                                @else
                                &darr;
                                &nbsp;
                                @endif
                            </a>
                        @else
                            <a href="?{{http_build_query(array_merge(\Request::input(), [ 'orderField' => $key, 'orderDirection' => 'asc']))}}">
                                {{$column}}
                                &uarr;
                                &darr;
                            </a>
                        @endif
                    @else
                        {{$column}}
                    @endif
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $rowData)
            @include($rowViewPath, [
                'data' => $rowData,
                'columns' => $columns
            ])
        @endforeach
        </tbody>
    </table>
</div>
<div class="table-pagination">
    {{$pagination}}
</div>