<tr>
    @foreach($columns as $key => $column)
        <td>
            {{array_get($rowData, $key)}}
        </td>
    @endforeach
</tr>