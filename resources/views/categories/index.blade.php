@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Категории</h1>
        <a href="{{ route('admin.categories.create') }}" class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition shadow-md">Создать</a>
    </div>
    <table class="w-full table-auto bg-white dark:bg-gray-700 rounded-lg shadow">
        <thead>
            <tr class="bg-gray-100 dark:bg-gray-600 text-left text-sm uppercase text-gray-600 dark:text-gray-300">
                <th class="p-3">Название</th>
                <th class="p-3">Складчин</th>
                <th class="p-3">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <tr class="border-t hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-600/50">
                <td class="p-3">{{ $cat->name }}</td>
                <td class="p-3">{{ $cat->skladchinas()->count() }}</td>
                <td class="p-3 flex space-x-2">
                    <a href="{{ route('admin.categories.edit', $cat) }}" class="text-blue-600 hover:underline">Редактировать</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500 hover:underline">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
