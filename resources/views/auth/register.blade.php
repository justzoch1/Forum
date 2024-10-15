<h1>Зарегистрироваться</h1>
<form method="POST" action="{{ route('auth.register') }}">
    @csrf
    <div>
        <label for="name">Логин:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <label for="email">Почта:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Зарегистрироваться</button>
</form>
