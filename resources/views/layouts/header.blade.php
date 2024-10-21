<div class="header">
    @auth
    <p>{{ auth()->user()->name }}</p>
    @endauth
    @guest
        <a href="{{route('login')}}">Войти</a>
        <a href="{{route('register')}}">Зарегистрироваться</a>
    @endguest
</div>
