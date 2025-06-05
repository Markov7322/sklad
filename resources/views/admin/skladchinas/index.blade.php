@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6 border-b pb-2">Складчины</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($skladchinas as $skladchina)
            <div>
                <x-skladchina-card :skladchina="$skladchina" />
                <div class="flex justify-between mt-2">
                    <a href="{{ route('admin.skladchinas.edit', $skladchina) }}" class="text-blue-500 hover:underline">Редактировать</a>
                    <a href="{{ route('admin.skladchinas.participants', $skladchina) }}" class="text-blue-500 hover:underline">Участники</a>
                    <form action="{{ route('admin.skladchinas.destroy', $skladchina) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500 hover:underline">Удалить</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
