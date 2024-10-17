@extends('layouts.main')
@section('content')

<form action="{{ route('comments.left', $topic->id) }}" method="POST">
    @csrf
    @method('POST')
    <div class="form-group">
        <label for="content">Контент</label>
        <textarea name="content" id="content" rows="3" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="user_id">Айди пользователя</label>
        <input name="user_id" id="user_id" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Оставить комментарий</button>
</form>
<form action="{{ route('topics.comments.search', $topic->id) }}" method="GET">
    @csrf
    @method('GET')
    <div class="form-group">
        <label for="content">Поиск</label>
        <input name="q" id="search" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Искать</button>
</form>
<form action="{{ route('topics.comments.sort', $topic->id) }}" method="GET">
    @csrf
    @method('GET')
    <div class="form-group">
        <label for="sort_by">Сортировать по:</label>
        <select name="by" id="sort_by" class="form-control">
            <option value="popular">Популярности</option>
            <option value="new">Дате загрузки</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Сортировать</button>
</form>
@if (count($items->comments) < 1)
    <p>Здесь пока ничего нет</p>
@endif
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
        <form action="{{ route('answers.create', [ $topic->id, $comment->id ]) }}" method="POST">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="content">Ваш ответ</label>
                <textarea name="content" id="content" rows="3" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Оставить ответ</button>
        </form>
        <ul>
            @foreach($comment->answers as $answer)
                <li>
                    <h4>Имя пользователя: {{ $answer->user_name }} Email: {{ $answer->user_email }} Тема: {{$comment->theme_name}} Дата публикации: ({{ $answer->created_at }})</h3>
                    <p>Контент: {{ $answer->content }}</p>
                </li>
                <div>
                    <form action="{{ route('comments.delete', $answer->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Удалить комментарий</button>
                    </form>
                    <form action="{{ route('comments.update', $answer->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="content">Контент</label>
                            <textarea name="content" id="content" rows="3" class="form-control" required>{{ $answer->content }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Обновить комментарий</button>
                    </form>
                </div>
            @endforeach
        </ul>
    @endforeach
</div>
@endsection
