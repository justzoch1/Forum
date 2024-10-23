<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/style.css">
<div class="container mt-4">
    <div class="text-left mb-3">
        <a href="javascript:history.back()" class="btn btn-primary">Назад</a>
    </div>
    <div class="alert alert-danger">
        <p class="mb-1"><strong>Статус код:</strong> {{ $fault['code'] }}</p>
        <p class="mb-1"><strong>Сообщение:</strong> {{ $fault['message'] }}</p>
    </div>
    @isset($fault['errors'])
        <div class="alert">
            <p class="mb-1"><strong>Ошибки:</strong></p>
            <ul class="list-unstyled">
                @foreach($fault['errors'] as $messages)
                    @foreach($messages as $message)
                        <li class="alert alert-warning">{{ htmlspecialchars($message) }}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endisset
</div>

