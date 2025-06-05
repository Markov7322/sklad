<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Мои складчины</h1>
    <a href="{{ route('skladchinas.create') }}" class="text-blue-500 mb-4 inline-block">Создать новую</a>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @forelse($skladchinas as $skladchina)
            <div>
                <x-skladchina-card :skladchina="$skladchina" />
                <div class="mt-2 text-center">
                    <a href="{{ route('skladchinas.edit', $skladchina) }}" class="text-blue-500">Редактировать</a>
                </div>
            </div>
        @empty
            <p>Вы еще не создали ни одной складчины.</p>
        @endforelse
    </div>
</x-app-layout>
