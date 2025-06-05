<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Складчины</h1>
    @if(auth()->check() && in_array(auth()->user()->role, ['admin','moderator','organizer'], true))
        <a href="{{ route('skladchinas.create') }}" class="text-blue-500">Создать</a>
    @endif
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
        @foreach($skladchinas as $skladchina)
            <x-skladchina-card :skladchina="$skladchina" />
        @endforeach
    </div>
</x-app-layout>
