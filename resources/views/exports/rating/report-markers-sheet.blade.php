<table>
    <thead>
    <tr>
        <th>Компетения</th>
        <th>Маркер</th>
        <th>Оценка внешних клиентов</th>
        <th>Оценка внутренних клиентов</th>
        <th>Оценка руководителя</th>
        <th>Самооценка</th>
    </tr>
    </thead>
    <tbody>
        @foreach($results as $result)
            @foreach($result['markers'] as $marker)
                <tr>
                    <td>{{$result['competence']}}</td>
                    <td>{{$marker['text']}}</td>
                    <td>{{$marker['ratings']['outer']}}</td>
                    <td>{{$marker['ratings']['inner']}}</td>
                    <td>{{$marker['ratings']['manager']}}</td>
                    <td>{{$marker['ratings']['self']}}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
