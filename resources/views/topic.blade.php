@extends('layouts.main')
@section('content')
<h1>Список топиков</h1>
<div>
    <p>Колличество элемментов: <b> {{$items->count}} </b></p>
    <ul>
        @foreach ($items->topics as $topic)
            <li>{{$topic->name}}</li>
        @endforeach
    </ul>
</div>
@endsection
