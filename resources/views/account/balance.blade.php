<x-app-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-xl font-bold mb-4">Баланс</h1>
            <p>Текущий баланс: {{ number_format(Auth::user()->balance, 2) }} ₽</p>
        </div>

        <div>
            <h2 class="text-lg font-semibold mb-4">Движение баланса</h2>
            <ul class="space-y-1">
                @forelse($transactions as $tr)
                    <li>{{ $tr->created_at->format('d.m H:i') }} - {{ $tr->description }}: {{ number_format($tr->amount, 2) }} ₽</li>
                @empty
                    <li>Пока нет операций.</li>
                @endforelse
            </ul>
            <div class="mt-4">{{ $transactions->links() }}</div>
        </div>
    </div>
</x-app-layout>
