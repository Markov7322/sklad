<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Баланс</h1>
    <p>Текущий баланс: {{ number_format(Auth::user()->balance, 2) }} ₽</p>
</x-app-layout>
