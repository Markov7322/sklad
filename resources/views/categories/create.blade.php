<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Новая категория</h1>
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        <input type="text" name="name" placeholder="Название" class="border p-2 block mb-2" />
        <input type="text" name="slug" placeholder="Slug" class="border p-2 block mb-2" />
        <textarea name="description" class="border p-2 block mb-2" placeholder="Описание"></textarea>
        <x-primary-button>Создать</x-primary-button>
    </form>
</x-app-layout>
