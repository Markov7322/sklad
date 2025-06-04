<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Редактировать категорию</h1>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $category->name }}" class="border p-2 block mb-2" />
        <input type="text" name="slug" value="{{ $category->slug }}" class="border p-2 block mb-2" />
        <textarea name="description" class="border p-2 block mb-2">{{ $category->description }}</textarea>
        <x-primary-button>Сохранить</x-primary-button>
    </form>
</x-app-layout>
