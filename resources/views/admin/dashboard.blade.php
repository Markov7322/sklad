<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Админ панель</h1>
    <ul class="list-disc pl-4">
        <li><a href="{{ route('admin.skladchinas.index') }}" class="text-blue-500">Складчины</a></li>
        <li><a href="{{ route('admin.categories.index') }}" class="text-blue-500">Категории</a></li>
        <li><a href="{{ route('admin.users.index') }}" class="text-blue-500">Пользователи</a></li>
    </ul>
</x-app-layout>
