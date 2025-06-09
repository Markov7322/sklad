@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6 border-b pb-2">
        <form method="GET" action="{{ route('admin.skladchinas.index') }}" class="flex items-center space-x-2">
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 border rounded-lg bg-gray-50 text-gray-900">
                <option value="">Все статусы</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <input type="hidden" name="view" value="{{ $viewMode }}" />
        </form>

        <h1 class="text-2xl font-semibold flex-grow text-center">Складчины</h1>

        @php
            $toggleView = ($viewMode === 'cards') ? 'table' : 'cards';
        @endphp
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.skladchinas.index', ['view' => $toggleView, 'status' => request('status')]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
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
            <a href="{{ route('admin.skladchinas.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
                </svg>
                Создать
            </a>
        </div>
    </div>

    @if($viewMode === 'cards')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($skladchinas as $skladchina)
                <x-admin-skladchina-card :skladchina="$skladchina" :preload="$loop->first" />
            @endforeach
        </div>
    @else
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Категория</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Взнос</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сбор</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($skladchinas as $index => $skladchina)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <a href="{{ route('admin.skladchinas.edit', $skladchina) }}" class="hover:underline">{{ $skladchina->name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($skladchina->category)->name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-semibold">{{ number_format($skladchina->member_price, 0, '', ' ') }} ₽</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ number_format($skladchina->full_price, 0, '', ' ') }} ₽</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-block px-2 py-1 text-white bg-green-500 rounded-full text-xs">{{ $skladchina->status_label }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('admin.skladchinas.edit', $skladchina) }}" class="text-blue-600 hover:underline">Редактировать</a>
                                <a href="{{ route('admin.skladchinas.participants', $skladchina) }}" class="text-blue-600 hover:underline">Участники</a>
                                <form action="{{ route('admin.skladchinas.destroy', $skladchina) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Удалить эту складчину?')">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
