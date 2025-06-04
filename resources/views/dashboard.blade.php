<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold mb-4">Мои складчины</h3>
                <ul>
                    @forelse($skladchinas as $item)
                        <li class="mb-2">
                            <a href="{{ route('skladchinas.show', $item) }}">{{ $item->name }}</a>
                            <span class="text-sm text-gray-500">({{ $item->category->name }})</span>
                        </li>
                    @empty
                        <li>Вы пока не участвуете в складчинах.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
