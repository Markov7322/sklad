<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold mb-4">Мои складчины</h3>

                <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium" role="tablist">
                        <li class="me-2">
                            <a href="{{ route('account.participations', ['tab' => 'participating', 'view' => $viewMode]) }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ $tab === 'participating' ? 'text-blue-600 border-blue-600' : 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300' }}" role="tab">Участвую</a>
                        </li>
                        @if(in_array(auth()->user()->role, ['admin','moderator','organizer'], true))
                            <li class="me-2">
                                <a href="{{ route('account.participations', ['tab' => 'organizing', 'view' => $viewMode]) }}" class="inline-block p-4 border-b-2 rounded-t-lg {{ $tab === 'organizing' ? 'text-blue-600 border-blue-600' : 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300' }}" role="tab">Организовываю</a>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <form method="GET" action="{{ route('account.participations') }}" class="flex items-center space-x-2">
                        <select name="status" onchange="this.form.submit()" class="px-3 py-1 border rounded bg-gray-50 text-sm">
                            <option value="">Все статусы</option>
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="tab" value="{{ $tab }}" />
                        <input type="hidden" name="view" value="{{ $viewMode }}" />
                    </form>

                    @php $toggleView = $viewMode === 'cards' ? 'table' : 'cards'; @endphp
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('account.participations', ['tab' => $tab, 'view' => $toggleView, 'status' => request('status')]) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
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
                        @if($tab === 'organizing' && in_array(auth()->user()->role, ['admin','moderator','organizer'], true))
                            <a href="{{ route('skladchinas.create') }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">+ Создать новую</a>
                        @endif
                    </div>
                </div>

                @if($tab === 'organizing' && in_array(auth()->user()->role, ['admin','moderator','organizer'], true))
                    @if($organizing->isEmpty())
                        <div class="text-gray-500 dark:text-gray-300">Вы ещё не создали ни одной складчины.</div>
                    @else
                        @if($viewMode === 'cards')
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($organizing as $skladchina)
                                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg transition">
                                        <x-skladchina-card :skladchina="$skladchina" :user="auth()->user()" />
                                        <div class="border-t px-4 py-3 text-center">
                                            <a href="{{ route('skladchinas.edit', $skladchina) }}" class="inline-block text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">✏️ Редактировать</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-900">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Название</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Категория</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Взнос</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Сбор</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Статус</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($organizing as $index => $skladchina)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    <a href="{{ route('skladchinas.edit', $skladchina) }}" class="hover:underline">{{ $skladchina->name }}</a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ optional($skladchina->category)->name ?? '—' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-semibold">{{ number_format($skladchina->member_price, 0, '', ' ') }} ₽</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ number_format($skladchina->full_price, 0, '', ' ') }} ₽</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <span class="inline-block px-2 py-1 text-white bg-green-500 rounded-full text-xs">{{ $skladchina->status_label }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('skladchinas.edit', $skladchina) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Редактировать</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                @else
                    @if($viewMode === 'cards')
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @forelse($participating as $item)
                                <x-skladchina-card :skladchina="$item" />
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
                                    @foreach($participating as $index => $item)
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
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
