<h1>Войти в аккаунт</h1>
<form method="POST" action="{{ route('auth.token') }}">
    @csrf
    <div>
        <label for="email">Почта:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Войти</button>
</form>
