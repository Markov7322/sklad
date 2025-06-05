<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Движение баланса</h1>
    <ul class="space-y-1">
        @forelse($transactions as $tr)
            <li>{{ $tr->created_at->format('d.m H:i') }} - {{ $tr->description }}: {{ number_format($tr->amount, 2) }} ₽</li>
        @empty
            <li>Пока нет операций.</li>
        @endforelse
    </ul>
</x-app-layout>
