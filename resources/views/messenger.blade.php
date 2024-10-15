@extends('layouts.main')
@section('content')

<div>
    @foreach($items->messages as $message)
        <h3>Имя пользователя: {{ $message->sender_name }}</h3>
            <p> Контент:{{ $message->content }}</p>
            <div>
                <form action="{{ route('messages.delete', $message->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить комментарий</button>
                </form>
                <form action="{{ route('messages.update', $message->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="content">Контент</label>
                        <textarea name="content" id="content" rows="3" class="form-control" required>{{ $message->content }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Обновить комментарий</button>
                </form>
            </div>
    @endforeach
</div>
@endsection
