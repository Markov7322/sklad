<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold mb-4">Профиль</h3>
                <p class="mb-1">Имя: {{ auth()->user()->name }}</p>
                <p class="mb-1">Email: {{ auth()->user()->email }}</p>
                <p class="mb-1">Роль: {{ auth()->user()->role }}</p>
                <p class="mb-1">Баланс: {{ number_format(auth()->user()->balance, 2) }} ₽</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold mb-4">Движение баланса</h3>
                <ul class="space-y-1">
                    @forelse($transactions as $tr)
                        <li>{{ $tr->created_at->format('d.m H:i') }} - {{ $tr->description }}: {{ number_format($tr->amount, 2) }} ₽</li>
                    @empty
                        <li>Пока нет операций.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold">Мои складчины</h3>
                    @php $toggleView = $viewMode === 'cards' ? 'table' : 'cards'; @endphp
                    <a href="{{ route('dashboard', ['view' => $toggleView]) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        @if($viewMode === 'cards')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 3h14v2H3V3zm0 4h7v2H3V7zm0 4h14v2H3v-2zm0 4h7v2H3v-2z" />
                            </svg>
                            Показать списком
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 3h4v4H4V3zm6 0h4v4h-4V3zm-6 6h4v4H4V9zm6 0h4v4h-4V9zm-6 6h4v4H4v-4zm6 6h4v4h-4v-4z" />
                            </svg>
                            Показать карточками
                        @endif
                    </a>
                </div>

                @if($viewMode === 'cards')
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($skladchinas as $item)
                            <x-skladchina-card :skladchina="$item" :preload="$loop->first" />
                        @empty
                            <p>Вы пока не участвуете в складчинах.</p>
                        @endforelse
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Оплачено</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Доступ до</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($skladchinas as $index => $item)
                                    @php $pivot = $item->pivot; @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <a href="{{ route('skladchinas.show', $item) }}" class="hover:underline">{{ $item->name }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-block px-2 py-1 text-xs rounded-full {{ $pivot->paid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $pivot->paid ? 'Оплачено' : 'Не оплачено' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">
                                            {{ $pivot->access_until ? \Carbon\Carbon::parse($pivot->access_until)->format('Y-m-d') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
