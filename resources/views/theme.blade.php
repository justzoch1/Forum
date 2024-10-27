@extends('layouts.main')
@section('template')
<!-- ########## ОБЛАСТЬ МОДУЛЯ (КОПИРУЕМ В ОСНОВНОЙ ШАБЛОН) ########## -->
<div id="ant106_post_wrap" class="ant106_post-blog_page_area">
    <div class="container">
        <div class="row">
            <main class="col-lg-8 ant106_post-blog-area">
                <div class="ant106_post-blog-details-content bg-white mb-4">
                    <h2 class="mb-4">{{ $items->topic->name }}</h2>
                    <div class="mt-3 mb-3">
                        <span class="ant106_post-date">{{ \Carbon\Carbon::parse($items->topic->created_at ) }}</span>
                        <span class="ant106_post-admin">Автор:<a href="#">{{$items->topic->user_name}}</a></span>
                    </div>
                    {{ $items->topic->description }}
                </div>
                <div class="ant106_post-related-post mb-4">
                    <h3 class="ant106_post-inner-title">Еще записи</h3>
                    <div class="row text-center">
                        @foreach($items->next as $topic)
                            <div class="col-md-6">
                                <div class="ant106_post-latest-news-box">
                                    <div class="ant106_post-latest-news-content">
                                        <h3><a href="{{ route('topics.get.one', $topic->id) }}">{{ $topic->name }}</a></h3>
                                        <span class="ant106_post-date">{{ \Carbon\Carbon::parse($topic->created_at ) }}</span>
                                        <p>{{ $topic->description }}</p>
                                        <ul class="ant106_post-blog-statistics">
                                            <li><i class="fas fa-comments"></i>{{ $topic->comments_count }}</li>
                                        </ul>
                                        <div class="ant106_post-news-btn">
                                            <a href="{{ route('topics.get.one', $topic->id) }}" class="ant106_post-theme-btn">Читать далее</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="ant106_comment-sort col-md-8 mb-4">
                    <form action="{{ route('topics.comments.sort', $items->topic->id) }}" method="GET" class="d-flex align-items-center mb-3">
                        @csrf
                        @method('GET')
                        <label for="sort_by" class="col-md-4 mr-3">Сортировать по:</label>
                        <select name="by" id="sort_by" class="form-control mr-2">
                            <option value="popular">Популярности</option>
                            <option value="new">Дате загрузки</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Сортировать</button>
                    </form>
                </div>
                <div class="ant106_post-comments mb-4">
                    <h3 class="ant106_post-inner-title">Комментарии</h3>
                    @foreach ($items->comments->data as $comment)
                        <div class="ant106_post-comment-item">
                            <div class="ant106_post-comment-content">
                                <h6><a href="{{ route('messenger', $comment->user_id )}}" class="text-dark">{{ $comment->user_name }}</a></h6>
                                <span class="ant106_post-date">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                <p>{{ $comment->content }}</p>
                                @auth
                                    @if(auth()->user()->id == $comment->user_id)
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
                                    @endif
                                @endauth
                                @can('create', App\Models\Comment::class)
                                    <a href="#" class="ant106_post-replay" onclick="toggleReplyForm(event, 'reply-form-{{ $comment->id }}')">Ответить</a>
                                    <div id="reply-form-{{ $comment->id }}" class="reply-form mb-3" style="display: none;">
                                        <form method="POST" action="{{ route('answers.create', [ $comment->id, $comment->user_id])}}">
                                            @csrf
                                            <textarea name="content" class="form-control" rows="3" placeholder="Ваш ответ..."></textarea>
                                            <button type="submit" class="btn btn-primary mt-2">Отправить</button>
                                        </form>
                                    </div>
                                @endcan
                            </div>
                        </div>
                        @foreach ($comment->answers as $answer)
                            <div class="ant106_post-comment-item ant106_post-replay-comment">
                                <div class="ant106_post-comment-content">
                                    <h6>От: <a href="{{ route('messenger', $answer->author_id )}}" class="text-dark">{{ $answer->author_name }}</a> Кому: <a href="{{ route('messenger', $answer->receiver_id )}}" class="text-dark">{{ $answer->receiver_name }}</a></h6>
                                    <span class="ant106_post-date">{{ \Carbon\Carbon::parse($answer->created_at)->diffForHumans() }}</span>
                                    <p>{{ $answer->content }}</p>
                                    @can('create', App\Models\Answer::class)
                                        <a href="#" class="ant106_post-replay" onclick="toggleReplyForm(event, 'reply-form-{{ $answer->id }}')">Ответить</a>
                                        <div id="reply-form-{{ $answer->id }}" class="reply-form mb-3" style="display: none;">
                                            <form method="POST" action="{{ route('answers.create', [$comment->id, $answer->user_id] ) }}">
                                                @csrf
                                                <textarea name="content" class="form-control" rows="3" placeholder="Ваш ответ..."></textarea>
                                                <button type="submit" class="btn btn-primary mt-2">Отправить</button>
                                            </form>
                                        </div>
                                    @endcan
                                </div>
                                @auth
                                    @if(auth()->user()->id == $answer->user_id)
                                        <form action="{{ route('answers.delete', $answer->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Удалить ответ</button>
                                        </form>
                                        <form action="{{ route('answers.update', $answer->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="content">Контент</label>
                                                <textarea name="content" id="content" rows="3" class="form-control" required>{{ $answer->content }}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Обновить ответ</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    @endforeach
                    <div class="col text-center">
                        <a href="" class="ant106_comments-theme-btn" data-topic="{{ $items->topic->id }}" id="loadMoreCommentsBtn" >Загрузить еще</a>
                    </div>
                </div>
            </main>

            <aside class="col-lg-4 ant106_post-ant106_post-widget-area">
                <div class="ant106_post-blog-sidebar">
                    <div class="ant106_post-widget">
                        <form class="ant106_post-search" method="GET" action="{{ route('topics.search') }}">
                            <span class="fas fa-search"></span>
                            <input name="q" id="search" type="search" placeholder="Поиск на сайте...">
                        </form>
                    </div>
                    <div class="ant106_post-widget news-ant106_post-widget">
                        <h3 class="ant106_post-widget-title">Последние записи</h3>
                        <ul class="ant106_post-list-style-one">
                            @foreach($items->latest as $topic)
                                <li><a href="{{ route('topics.get.one', $topic->id) }}">{{ $topic->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </aside>
            <div class="ant106_post-comment-form col-md-8">
                <h3 class="ant106_post-inner-title"> Оставить комментарий</h3>
                <form id="ant106_post-comment-form" class="ant106_post-comment-form" action="{{ route('comments.left', $items->topic->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="captcha">
                        {!! app('captcha')->render() !!}
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea class="form-control" rows="7" placeholder="Сообщение..." required name="content" id="content"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button id="add-more-comments-bt"n class="ant106_post-theme-btn mt-2" type="submit">Отправить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Container /- -->
</div>
<script src="/js/ajax_paginator.js"></script>
@endsection
