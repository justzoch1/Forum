<div class="header bg-light p-3 d-flex justify-content-between align-items-center">
    <div>
        @auth
            <a class="btn btn-link me-4">{{ auth()->user()->name }}</a>
            <a href="{{ route('blog.index') }}" class="btn btn-link">Блог</a>
            <a href="{{ route('notifications.list') }}" class="btn btn-link">Уведомления</a>
        @endauth
        @guest
            <a href="{{ route('login') }}" class="btn btn-link">Войти</a>
            <a href="{{ route('register') }}" class="btn btn-link">Зарегистрироваться</a>
        @endguest
    </div>
    @auth
        <div class="ms-auto">
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
            <a href="#" class="btn btn-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выйти</a>
        </div>
    @endauth
</div>
