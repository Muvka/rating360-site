<table>
    <thead>
    <tr>
        @foreach($data['columns'] as $column)
            <th>{{$column['label']}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($data['data'] as $row)
        <tr>
            @foreach($data['columns'] as $column)
                <td>{{array_key_exists($column['key'], $row) ? $row[$column['key']] : ''}}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
