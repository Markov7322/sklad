@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Складчины пользователя {{ $user->name }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($skladchinas as $skladchina)
            <x-skladchina-card :skladchina="$skladchina" :user="$user" />
        @empty
            <p class="text-gray-600 dark:text-gray-300">Пользователь не участвует в складчинах.</p>
        @endforelse
    </div>
</div>
@endsection
