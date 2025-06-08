<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Заголовок --}}
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Категории</h1>

        @foreach($categories as $category)
            {{-- Название категории --}}
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
                <a href="{{ route('categories.show', $category->slug) }}" class="hover:underline">{{ $category->name }}</a>
            </h2>

            {{-- Сетка карточек складчин --}}
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-12">
                @foreach($category->skladchinas as $skladchina)
                    <x-home-skladchina-card :skladchina="$skladchina" />
                @endforeach
            </div>
        @endforeach
    </div>
</x-app-layout>
