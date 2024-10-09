<div>
    <ul>
        @foreach ($topics as $topic)
            <li>{{$topic->name}}</li>
        @endforeach
    </ul>
</div>
