@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Складчины пользователя {{ $user->name }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($skladchinas as $skladchina)
            <div class="bg-white dark:bg-gray-700 rounded-xl shadow-md overflow-hidden">
                @if($skladchina->image_path)
                    <img src="{{ asset('storage/'.$skladchina->image_path) }}" class="w-full h-40 object-cover" />
                @endif
                <div class="p-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">{{ $skladchina->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $skladchina->category->name ?? '' }}</p>
                    <p class="text-sm mt-2">Цена: <strong>{{ $skladchina->full_price }} ₽</strong></p>
                    <p class="text-sm">Взнос: <strong>{{ $skladchina->member_price }} ₽</strong></p>
                </div>
            </div>
        @empty
            <p class="text-gray-600 dark:text-gray-300">Пользователь не участвует в складчинах.</p>
        @endforelse
    </div>
</div>
@endsection
