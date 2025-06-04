<x-app-layout>
    <h1 class="text-xl font-bold mb-4">{{ $skladchina->name }}</h1>
    @if($skladchina->image_path)
        <img src="{{ asset('storage/'.$skladchina->image_path) }}" alt="{{ $skladchina->name }}" class="mb-4 w-full h-64 object-cover rounded">
    @endif
    <p>{{ $skladchina->description }}</p>
    <p class="mt-2">Полная цена: {{ $skladchina->full_price }}</p>
    <p>Цена взноса: {{ $skladchina->member_price }}</p>
    @auth
        @if(! $skladchina->participants->contains(Auth::id()))
            <form method="POST" action="{{ route('skladchinas.join', $skladchina) }}" class="mt-4">
                @csrf
                <x-primary-button>Записаться</x-primary-button>
            </form>
        @else
            <p class="mt-4 text-green-500">Вы участвуете</p>
        @endif
    @endauth
</x-app-layout>
