@extends('layouts.main')
@section('template')
    <div id="ant106_post_wrap" class="ant106_post-blog_page_area">
    <div class="container">
        <div class="row">
            <main class="col-lg-8 ant106_post-blog-area">
                <div class="row">
                    @foreach($items->popular->data as $topic)
                        <div class="col-lg-6 col-md-6">
                            <div class="ant106_post-latest-news-box text-center">
                                <div class="ant106_post-latest-news-content">
                                    <h3><a href="{{ route('topics.get.one', $topic->id) }}">{{ $topic->name }}</a></h3>
                                    <span class="ant106_post-date">{{ \Carbon\Carbon::parse($topic->created_at) }}</span>
                                    <p>{{$topic->preview}}</p>
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
                    <div class="col text-center">
                        <a href="#" class="ant106_post-theme-btn" id="loadMoreBtn">Следующая страница</a>
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
        </div>
    </div>
    <!-- Container /- -->
</div>

@endsection
