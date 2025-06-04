<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Складчины</h1>
    <a href="{{ route('skladchinas.create') }}" class="text-blue-500">Создать</a>
    <ul class="mt-4">
        @foreach($skladchinas as $skladchina)
            <li>
                <a href="{{ route('skladchinas.show', $skladchina) }}">{{ $skladchina->name }}</a>
            </li>
        @endforeach
    </ul>
</x-app-layout>
