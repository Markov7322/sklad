<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8 space-y-8 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-8">
        <div class="lg:col-span-1 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-4 text-center lg:text-left">Баланс</h1>
            <p class="text-center lg:text-left text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format(Auth::user()->balance, 2) }} ₽</p>
            <form method="POST" action="{{ route('topups.store') }}" class="mt-6 flex flex-col sm:flex-row sm:items-center sm:space-x-3">
                @csrf
                <input type="number" step="0.01" name="amount" class="border rounded p-2 w-full sm:w-32 mb-3 sm:mb-0" placeholder="Сумма">
                <x-primary-button class="w-full sm:w-auto">Пополнить баланс</x-primary-button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Движение баланса</h2>
            <ul class="space-y-2 text-sm">
                @forelse($transactions as $tr)
                    <li class="flex justify-between">
                        <span>{{ $tr->created_at->format('d.m H:i') }} - {{ $tr->description }}</span>
                        <span>{{ number_format($tr->amount, 2) }} ₽</span>
                    </li>
                @empty
                    <li>Пока нет операций.</li>
                @endforelse
            </ul>
            <div class="mt-4">{{ $transactions->links() }}</div>
        </div>
    </div>
</x-app-layout>
