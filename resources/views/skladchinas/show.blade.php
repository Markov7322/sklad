<x-app-layout>
    <h1 class="text-xl font-bold mb-4">{{ $skladchina->name }}</h1>
    <p>{{ $skladchina->description }}</p>
    <p>Цена: {{ $skladchina->member_price }}</p>
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
