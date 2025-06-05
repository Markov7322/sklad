<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Мои складчины</h1>
            <div class="flex items-center space-x-4">
                @php $toggleView = $viewMode === 'cards' ? 'table' : 'cards'; @endphp
                <a href="{{ route('organizer.skladchinas.index', ['view' => $toggleView]) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white dark:text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    @if($viewMode === 'cards')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 3h14v2H3V3zm0 4h7v2H3V7zm0 4h14v2H3v-2zm0 4h7v2H3v-2z" />
                        </svg>
                        Показать таблицей
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 3h4v4H4V3zm6 0h4v4h-4V3zm-6 6h4v4H4V9zm6 0h4v4h-4V9zm-6 6h4v4H4v-4zm6 6h4v4h-4v-4z" />
                        </svg>
                        Показать карточками
                    @endif
                </a>
                <a href="{{ route('skladchinas.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white dark:text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                    + Создать новую
                </a>
            </div>
        </div>

        @if($skladchinas->isEmpty())
            <div class="text-gray-500 dark:text-gray-300">
                Вы ещё не создали ни одной складчины.
            </div>
        @else
            @if($viewMode === 'cards')
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($skladchinas as $skladchina)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg transition">
                            <x-skladchina-card :skladchina="$skladchina" :user="auth()->user()" />
                            <div class="border-t px-4 py-3 text-center">
                                <a href="{{ route('skladchinas.edit', $skladchina) }}"
                                   class="inline-block text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                    ✏️ Редактировать
                                </a>
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
                            @foreach($skladchinas as $index => $skladchina)
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
    </div>
</x-app-layout>
