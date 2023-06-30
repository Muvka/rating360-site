<table>
    <thead>
    <tr>
        <th>Компетения</th>
        <th>С самооценкой (общий)</th>
        <th>Без самооценки (общий)</th>
        <th>Оценка внешних клиентов</th>
        <th>Оценка внутренних клиентов</th>
        <th>Оценка руководителя</th>
        <th>Самооценка</th>
    </tr>
    </thead>
    <tbody>
        @foreach($results as $result)
            <tr>
                <td>{{$result['competence']}}</td>
                <td>{{$result['averageRating']}}</td>
                <td>{{$result['averageRatingWithoutSelf']}}</td>
                <td>{{$result['ratings']['outer']}}</td>
                <td>{{$result['ratings']['inner']}}</td>
                <td>{{$result['ratings']['manager']}}</td>
                <td>{{$result['ratings']['self']}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
