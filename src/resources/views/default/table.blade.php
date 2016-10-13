<div class="table-filter">

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