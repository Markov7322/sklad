<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Категории</h1>
    @foreach($categories as $category)
        <h2 class="text-lg font-semibold mt-4">{{ $category->name }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-2">
            @foreach($category->skladchinas as $skladchina)
                <div class="border p-4 rounded shadow">
                    @if($skladchina->image_path)
                        <img src="{{ asset('storage/'.$skladchina->image_path) }}" alt="{{ $skladchina->name }}" class="mb-2 w-full h-40 object-cover rounded">
                    @endif
                    <h3 class="font-bold">{{ $skladchina->name }}</h3>
                    <p class="text-sm mb-1">{{ $skladchina->description }}</p>
                    <p class="text-sm">Цена: {{ $skladchina->full_price }} | Взнос: {{ $skladchina->member_price }}</p>
                    @auth
                        @if(! $skladchina->participants->contains(Auth::id()))
                            <form method="POST" action="{{ route('skladchinas.join', $skladchina) }}" class="mt-2">
                                @csrf
                                <x-primary-button>Записаться</x-primary-button>
                            </form>
                        @else
                            <p class="mt-2 text-green-500">Вы участвуете</p>
                        @endif
                    @endauth
                </div>
            @endforeach
        </div>
    @endforeach
</x-app-layout>
