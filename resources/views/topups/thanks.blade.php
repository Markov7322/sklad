<x-app-layout>
    <div class="space-y-6">
        <h1 class="text-xl font-bold">Пополнение баланса</h1>
        <p>Заказ №{{ $topup->id }} на сумму {{ number_format($topup->amount, 2) }} ₽ создан.</p>
        <p>Переведите указанную сумму на карту, указанную ниже. После подтверждения администратором средства поступят на ваш баланс.</p>
        <p class="font-semibold">Карта для оплаты: <span class="select-all">1234 5678 9012 3456</span></p>
        <a href="{{ route('account.balance') }}" class="text-blue-500 underline">Вернуться к балансу</a>
    </div>
</x-app-layout>
