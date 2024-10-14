@extends('layouts.main')
@section('content')

<form action="{{ route('comments.left') }}" method="POST">
    @csrf
    @method('POST')
    <div class="form-group">
        <label for="content">Контент</label>
        <textarea name="content" id="content" rows="3" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="theme_id">Айди темы</label>
        <input name="theme_id" id="theme_id" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="user_id">Айди пользователя</label>
        <input name="user_id" id="user_id" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Оставить комментарий</button>
</form>

<div>
    @foreach($items->comments as $comment)
        <h3>Имя пользователя: {{ $comment->user_name }} Email: {{ $comment->user_email }} Тема: {{$comment->theme_name}}</h4>
        <p> Контент:{{ $comment->content }}</p>
        <div>
            <form action="{{ route('comments.delete', $comment->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Удалить комментарий</button>
            </form>
            <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="content">Контент</label>
                    <textarea name="content" id="content" rows="3" class="form-control" required>{{ $comment->content }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Обновить комментарий</button>
            </form>
        </div>
        <ul>
            @foreach($comment->answers as $answer)
                <li>
                    <h4>Имя пользователя: {{ $answer->user_name }} Email: {{ $answer->user_email }} Тема: {{$comment->theme_name}} Дата публикации: ({{ $answer->created_at }})</h3>
                    <p>Контент: {{ $answer->content }}</p>
                </li>
            @endforeach
        </ul>
    @endforeach
</div>
@endsection
