@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6 border-b pb-2">Пользователи</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($users as $user)
            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $user->name }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $user->email }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Роль: {{ $user->role }}</p>
                <div class="flex justify-between mt-4">
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Редактировать</a>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Удалить</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
