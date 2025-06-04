@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6 border-b pb-2">Редактировать категорию</h1>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $category->name }}" class="w-full border rounded p-2" />
        <input type="text" name="slug" value="{{ $category->slug }}" class="w-full border rounded p-2" />
        <textarea name="description" class="w-full border rounded p-2">{{ $category->description }}</textarea>
        <x-primary-button class="mt-2">Сохранить</x-primary-button>
    </form>
@endsection
