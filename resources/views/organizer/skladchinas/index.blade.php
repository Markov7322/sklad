<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Мои складчины</h1>
    <a href="{{ route('skladchinas.create') }}" class="text-blue-500 mb-4 inline-block">Создать новую</a>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @forelse($skladchinas as $skladchina)
            <div class="border p-4 rounded shadow">
                @if($skladchina->image_path)
                    <img src="{{ asset('storage/'.$skladchina->image_path) }}" alt="{{ $skladchina->name }}" class="mb-2 w-full h-40 object-cover rounded">
                @endif
                <a href="{{ route('skladchinas.show', $skladchina) }}" class="font-semibold block mb-1">{{ $skladchina->name }}</a>
                <p class="text-sm text-gray-500 mb-2">{{ $skladchina->category->name }}</p>
                <p class="text-sm mb-2">Цена: {{ number_format($skladchina->full_price, 0, '', ' ') }} ₽ | Взнос: {{ number_format($skladchina->member_price, 0, '', ' ') }} ₽</p>
                <a href="{{ route('skladchinas.edit', $skladchina) }}" class="text-blue-500">Редактировать</a>
            </div>
        @empty
            <p>Вы еще не создали ни одной складчины.</p>
        @endforelse
    </div>
</x-app-layout>
