@extends('layouts.main')

@section('template')
    <div class="container mt-5">
        @if($items->count < 1)
            <div class="alert alert-info" role="alert">
                У вас пока нет сообщений с этим пользователем. Напишите первым!
            </div>
        @else
            <div class="list-group">
                @foreach($items->messages as $message)
                    <div class="list-group-item mb-3">
                        <h5 class="mb-1">Имя пользователя: {{ $message->sender_name }}</h5>
                        <p class="mb-1">Контент: {{ $message->content }}</p>
                        @if(auth()->user()->id == $message->sender_id)
                            <div class="btn-group" role="group">
                                <form action="{{ route('messages.delete', $message->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Удалить комментарий</button>
                                </form>
                                <form action="{{ route('messages.update', $message->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="content-{{ $message->id }}">Обновить контент</label>
                                        <textarea name="content" id="content-{{ $message->id }}" rows="1" class="form-control" required>{{ $message->content }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Обновить комментарий</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4">
            <h4>Оставить сообщение</h4>
            <form action="{{ route('messages.left', Route::current()->parameter('receiver')) }}" method="POST">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label for="content">Контент</label>
                    <textarea name="content" id="content" rows="3" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Оставить сообщение</button>
            </form>
        </div>
    </div>
@endsection
