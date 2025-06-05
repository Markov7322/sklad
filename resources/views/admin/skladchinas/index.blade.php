@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6 border-b pb-2">Складчины</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($skladchinas as $skladchina)
            <div class="bg-white dark:bg-gray-700 rounded-xl shadow-md overflow-hidden">
                @if($skladchina->image_path)
                    <img src="{{ asset('storage/'.$skladchina->image_path) }}" class="w-full h-40 object-cover" />
                @endif
                <div class="p-4">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 rounded-full mb-2 {{ $skladchina->status_badge_classes }}">{{ $skladchina->status_label }}</span>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">{{ $skladchina->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ Str::limit($skladchina->description, 80) }}</p>
                    <p class="text-sm mt-2">Цена: <strong>{{ $skladchina->full_price }} ₽</strong></p>
                    <p class="text-sm">Взнос: <strong>{{ $skladchina->member_price }} ₽</strong></p>
                    <div class="flex justify-between mt-4">
                        <a href="{{ route('admin.skladchinas.edit', $skladchina) }}" class="text-blue-500 hover:underline">Редактировать</a>
                        <a href="{{ route('admin.skladchinas.participants', $skladchina) }}" class="text-blue-500 hover:underline">Участники</a>
                        <form action="{{ route('admin.skladchinas.destroy', $skladchina) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500 hover:underline">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
