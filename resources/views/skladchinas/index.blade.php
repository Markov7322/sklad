<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Складчины</h1>
    @auth
        <a href="{{ route('skladchinas.create') }}" class="text-blue-500">Создать</a>
    @endauth
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
        @foreach($skladchinas as $skladchina)
            <div class="border rounded p-4 shadow">
                @if($skladchina->image_path)
                    <img src="{{ asset('storage/'.$skladchina->image_path) }}" alt="{{ $skladchina->name }}" class="mb-2 w-full h-40 object-cover rounded">
                @endif
                <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 rounded-full mb-2 {{ $skladchina->status_badge_classes }}">{{ $skladchina->status_label }}</span>
                <h3 class="font-semibold text-lg">
                    <a href="{{ route('skladchinas.show', $skladchina) }}">{{ $skladchina->name }}</a>
                </h3>
                <p class="text-sm text-gray-500 mb-1">{{ $skladchina->category->name }}</p>
                <p class="text-sm">Цена: {{ $skladchina->full_price }} | Взнос: {{ $skladchina->member_price }}</p>
            </div>
        @endforeach
    </div>
</x-app-layout>
