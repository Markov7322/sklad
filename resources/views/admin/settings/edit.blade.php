@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6 border-b pb-2">Настройки</h1>
    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
        @csrf
        <input type="number" name="organizer_share_percent" value="{{ $percent }}" class="border rounded p-2" step="0.01" />
        <x-primary-button>Сохранить</x-primary-button>
    </form>
@endsection
