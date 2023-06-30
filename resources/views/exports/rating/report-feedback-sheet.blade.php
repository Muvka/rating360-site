<table>
    <thead>
    <tr>
        <th>Маркер</th>
        <th>Ответ</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{$result['text']}}</td>
            <td>{{$result['answer']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
