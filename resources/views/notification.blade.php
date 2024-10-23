@extends('layouts.main')

@section('template')
    <div class="container mt-5">
        <h1 class="mb-4">Ваши уведомления</h1>
        <p class="lead">Количество: {{ $items->count }}</p>

        @if($items->count > 0)
            <div class="list-group">
                @foreach ($items->notifications as $notification)
                    <div class="list-group-item mb-2">
                        <a href="{{ route('messenger', $notification->data->sender_id ) }}">{{ $notification->data->message }}</a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info" role="alert">
                У вас нет новых уведомлений.
            </div>
        @endif
    </div>
@endsection
