<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Категории</h1>
    <a href="{{ route('admin.categories.create') }}" class="text-blue-500">Создать</a>
    <ul class="mt-4">
        @foreach($categories as $category)
            <li class="mb-2">
                {{ $category->name }}
                <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-500 ml-2">Редактировать</a>
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 ml-2">Удалить</button>
                </form>
            </li>
        @endforeach
    </ul>
</x-app-layout>
