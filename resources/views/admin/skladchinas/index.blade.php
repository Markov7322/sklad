@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6 border-b pb-2">
        <h1 class="text-2xl font-semibold">Складчины</h1>
        @php
            $toggleView = ($viewMode === 'cards') ? 'table' : 'cards';
        @endphp
        <a href="{{ route('admin.skladchinas.index', ['view' => $toggleView]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
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
            @foreach($skladchinas as $skladchina)
                <div class="bg-white rounded-2xl shadow hover:shadow-lg overflow-hidden flex flex-col">
                    @if($skladchina->image_path)
                        <div class="w-full h-48 overflow-hidden relative group">
                            <img src="{{ asset('storage/'.$skladchina->image_path) }}" alt="{{ $skladchina->name }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @if($skladchina->images->first())
                                <img src="{{ asset('storage/'.$skladchina->images->first()->path) }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                            @endif
                        </div>
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">Нет изображения</span>
                        </div>
                    @endif

                    <div class="px-4 pt-3">
                        <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-green-500 rounded-full">
                            {{ $skladchina->status_label }}
                        </span>
                    </div>

                    <div class="px-4 mt-2 flex-shrink">
                        <h3 class="text-lg font-bold text-gray-900 leading-snug">
                            <a href="{{ route('admin.skladchinas.edit', $skladchina) }}" class="hover:underline">
                                {{ $skladchina->name }}
                            </a>
                        </h3>
                    </div>

                    <div class="px-4 mt-3 text-gray-700">
                        <span class="text-sm font-medium">Взнос:&nbsp;</span>
                        <span class="text-blue-600 font-semibold">{{ number_format($skladchina->member_price, 0, '', ' ') }} ₽</span>
                        <span class="mx-2">|</span>
                        <span class="text-sm font-medium">Сбор:&nbsp;</span>
                        <span class="font-semibold">{{ number_format($skladchina->full_price, 0, '', ' ') }} ₽</span>
                    </div>

                    <div class="px-4 mt-2 text-sm text-gray-600">
                        <span class="font-medium">Категория:</span>
                        <span>{{ optional($skladchina->category)->name ?? '—' }}</span>
                    </div>

                    <div class="mt-auto px-4 pb-4 pt-4">
                        @php
                            $user = auth()->user();
                            $isSubscribed = $user && $user->isSubscribed($skladchina);
                            $hasPaid      = $isSubscribed && $skladchina->participants()->where('user_id', $user->id)->first()?->pivot->paid;
                        @endphp

                        @if($isSubscribed)
                            @if($hasPaid)
                                <button disabled class="w-full bg-green-600 text-white font-semibold px-4 py-2 rounded-lg">Вы участвуете (Оплачено)</button>
                            @else
                                <button disabled class="w-full bg-yellow-500 text-white font-semibold px-4 py-2 rounded-lg">Вы участвуете (Не оплачено)</button>
                            @endif
                        @else
                            <form action="{{ route('admin.skladchinas.participants', $skladchina) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-700 transition">Участвовать</button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="flex justify-between mt-2 px-1">
                    <a href="{{ route('admin.skladchinas.edit', $skladchina) }}" class="text-blue-500 hover:underline text-sm">Редактировать</a>
                    <a href="{{ route('admin.skladchinas.participants', $skladchina) }}" class="text-blue-500 hover:underline text-sm">Участники</a>
                    <form action="{{ route('admin.skladchinas.destroy', $skladchina) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-sm" onclick="return confirm('Удалить эту складчину?')">Удалить</button>
                    </form>
                </div>
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
