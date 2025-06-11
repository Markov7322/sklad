@extends('layouts.admin')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Отправить пуш</h1>
    <form method="POST" action="{{ route('admin.push.send') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1">Заголовок</label>
            <input type="text" name="title" class="w-full border rounded" required>
        </div>
        <div>
            <label class="block mb-1">Сообщение</label>
            <textarea name="message" class="w-full border rounded" required></textarea>
        </div>
        <div>
            <label class="block mb-1">Ссылка (необязательно)</label>
            <input type="text" name="url" class="w-full border rounded">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Отправить</button>
    </form>
@endsection
