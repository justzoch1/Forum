@extends('layouts.app')
@section('content')
    <div>
    <h1>Ваши уведоиления</h1>
    <p>Колличество: {{ $items->count }}</p>
    @foreach ( $items->notifications as $notification )
        <p>{{ $notification->data[0] }}</p>
    @endforeach
</div>
@endsection
