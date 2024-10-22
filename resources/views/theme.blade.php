@extends('layouts.main')
@section('template')
<!-- ########## ОБЛАСТЬ МОДУЛЯ (КОПИРУЕМ В ОСНОВНОЙ ШАБЛОН) ########## -->
<div id="ant106_post_wrap" class="ant106_post-blog_page_area">
    <div class="container">
        <div class="row">
            <main class="col-lg-8 ant106_post-blog-area">
                <div class="ant106_post-blog-details-content bg-white">
                    <h2 class="mb-4">{{ $items->topic->name }}</h2>
                    <img src="/img/single.jpg" alt="">
                    <div class="mt-3 mb-3">
                        <span class="ant106_post-date">{{ \Carbon\Carbon::parse($items->topic->created_at ) }}</span>
                        <span class="ant106_post-admin">От:<a href="#">Админ</a></span>
                    </div>
                    {{ $items->topic->description }}
                    </div>
                <div class="ant106_post-related-post mb-4">
                    <h3 class="ant106_post-inner-title">Еще записи</h3>
                    <div class="row text-center">
                        @foreach($items->next->data as $topic)
                        <div class="col-md-6">
                            <div class="ant106_post-latest-news-box">
                                <div class="ant106_post-latest-news-img">
                                    <img src="/img/b-1.jpg" alt="">
                                </div>
                                <div class="ant106_post-latest-news-content">
                                    <h3><a href="#!">{{ $topic->name }}</a></h3>
                                    <span class="ant106_post-date">{{ \Carbon\Carbon::parse($topic->created_at ) }}$</span>
                                    <p>{{ $topic->description }}</p>
                                    <ul class="ant106_post-blog-statistics">
                                        <li><i class="fas fa-comments"></i>{{ $topic->comments_count }}</li>
                                    </ul>
                                    <div class="ant106_post-news-btn">
                                        <a href="#!" class="ant106_post-theme-btn">Читать далее</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="ant106_post-comments mb-4">
                    <h3 class="ant106_post-inner-title">Комментарии</h3>
                    @foreach ($items->comments->data as $comment)
                        <div class="ant106_post-comment-item">
                            <div class="ant106_post-comment-img">
                                <img src="/img/comment-1.png" alt="">
                            </div>
                            <div class="ant106_post-comment-content">
                                <h6>{{ $comment->user_name }}</h6>
                                <span class="ant106_post-date">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                <p>{{ $comment->content }}</p>
                                <a href="#" class="ant106_post-replay">Ответить</a>
                            </div>
                        </div>
                        @foreach ($comment->answers as $answer)
                            <div class="ant106_post-comment-item ant106_post-replay-comment">
                                <div class="ant106_post-comment-img">
                                    <img src="/img/comment-2.png" alt="">
                                </div>
                                <div class="ant106_post-comment-content">
                                    <h6>{{ $answer->user_name }}</h6>
                                    <span class="ant106_post-date">{{ \Carbon\Carbon::parse($answer->created_at)->diffForHumans() }}</span>
                                    <p>{{ $answer->content }}</p>
                                    <a href="#" class="ant106_post-replay">Ответить</a>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
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
                            @foreach($items->latest->data as $topic)
                                <li><a href="#!">{{ $topic->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </aside>

            <div class="ant106_post-comment-form">
                <h3 class="ant106_post-inner-title">Написать комментарий</h3>
                <form id="ant106_post-comment-form" name="comment_form" class="ant106_post-comment-form" action="#" method="POST">
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="first_name" id="name" class="form-control" value="" placeholder="Ваше имя" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" name="email" id="email" class="form-control" value="" placeholder="yourmail@gmail.com" required="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="message" id="message" class="form-control" rows="7" placeholder="Сообщение..." required=""></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <button class="ant106_post-theme-btn mt-2" type="submit">Отправить</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Container /- -->
</div>
@endsection
