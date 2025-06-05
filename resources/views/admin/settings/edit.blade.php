@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6 border-b pb-2">Настройки</h1>
    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
        @csrf
        <label class="block">
            <span class="text-gray-700 dark:text-gray-300">Процент организатору</span>
            <input type="number" name="organizer_share_percent" value="{{ $percent }}" class="border rounded p-2 w-full" step="0.01" />
        </label>
        <label class="block">
            <span class="text-gray-700 dark:text-gray-300">Скидка на повторное участие (%)</span>
            <input type="number" name="repeat_discount_percent" value="{{ $discount }}" class="border rounded p-2 w-full" step="0.01" />
        </label>
        <label class="block">
            <span class="text-gray-700 dark:text-gray-300">Количество дней доступа</span>
            <input type="number" name="default_access_days" value="{{ $days }}" class="border rounded p-2 w-full" />
        </label>
        <x-primary-button>Сохранить</x-primary-button>
    </form>
@endsection
