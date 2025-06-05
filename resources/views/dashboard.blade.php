<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold mb-4">Профиль</h3>
                <p class="mb-1">Имя: {{ auth()->user()->name }}</p>
                <p class="mb-1">Email: {{ auth()->user()->email }}</p>
                <p class="mb-1">Роль: {{ auth()->user()->role }}</p>
                <p class="mb-1">Баланс: {{ number_format(auth()->user()->balance, 2) }} ₽</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold mb-4">Мои складчины</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @forelse($skladchinas as $item)
                        <div class="border p-4 rounded shadow">
                            @if($item->image_path)
                                <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->name }}" class="mb-2 w-full h-40 object-cover rounded">
                            @endif
                            <a href="{{ route('skladchinas.show', $item) }}" class="font-semibold">{{ $item->name }}</a>
                            <p class="text-sm text-gray-500">{{ $item->category->name }}</p>
                        </div>
                    @empty
                        <p>Вы пока не участвуете в складчинах.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
