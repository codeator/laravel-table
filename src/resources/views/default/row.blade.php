<tr>
    @foreach($columns as $key => $column)
        <td>
            {{array_get($rowData, $key)}}
        </td>
    @endforeach
    @if(count($actions))
        <td>
            @foreach($actions as $route => $name)
                <a href="{{route($route, $rowData)}}">{{$name}}</a>
            @endforeach
        </td>
    @endif
</tr>