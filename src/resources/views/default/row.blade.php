<tr>
    @if ($hasBatchActions)
        <td onclick="toggleInnerCheckbox();">
            <input type="checkbox" name="b[]" value="{{array_get($rowData, 'id')}}">
        </td>
    @endif
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