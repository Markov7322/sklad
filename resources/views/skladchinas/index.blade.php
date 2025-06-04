<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Складчины</h1>
    @auth
        <a href="{{ route('skladchinas.create') }}" class="text-blue-500">Создать</a>
    @endauth
    <ul class="mt-4">
        @foreach($skladchinas as $skladchina)
            <li class="mb-2">
                <a href="{{ route('skladchinas.show', $skladchina) }}">{{ $skladchina->name }}</a>
                <span class="text-sm text-gray-500">({{ $skladchina->category->name }})</span>
            </li>
        @endforeach
    </ul>
</x-app-layout>
