@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Складчины пользователя {{ $user->name }}</h1>
        @php $toggleView = $viewMode === 'cards' ? 'table' : 'cards'; @endphp
        <a href="{{ route('admin.users.participations', ['user' => $user, 'view' => $toggleView]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
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
    </div>

    @if($viewMode === 'cards')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($skladchinas as $skladchina)
                <x-skladchina-card :skladchina="$skladchina" :user="$user" />
            @empty
                <p class="text-gray-600 dark:text-gray-300">Пользователь не участвует в складчинах.</p>
            @endforelse
        </div>
    @else
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Название</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Оплачено</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Доступ до</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($skladchinas as $index => $skladchina)
                        @php $pivot = $skladchina->pivot; @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <a href="{{ route('skladchinas.show', $skladchina) }}" class="hover:underline">{{ $skladchina->name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-block px-2 py-1 text-xs rounded-full {{ $pivot->paid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $pivot->paid ? 'Оплачено' : 'Не оплачено' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700 dark:text-gray-300">
                                {{ $pivot->access_until ? \Carbon\Carbon::parse($pivot->access_until)->format('Y-m-d') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
