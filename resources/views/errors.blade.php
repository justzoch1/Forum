@extends('layouts.app')
@section('content')
<div>
    <p>Статус код: {{ $fault['code'] }}</p>
    <p>Сообщение: {{ $fault['message'] }}</p>
    @isset($fault['errors'])
    <p>Ошибки: {{ $fault['errors'] }}</p>
    @endisset
</div>
@endsection
