@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6 border-b pb-2">Новая категория</h1>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
        @csrf
        <input type="text" name="name" placeholder="Название" class="w-full border rounded p-2" />
        <input type="text" name="slug" placeholder="Slug" class="w-full border rounded p-2" />
        <textarea name="description" class="w-full border rounded p-2" placeholder="Описание"></textarea>
        <x-primary-button class="mt-2">Создать</x-primary-button>
    </form>
@endsection
