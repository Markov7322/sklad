@extends('layouts.admin')
@php use Illuminate\Support\Str; @endphp
@section('content')
    <h1 class="text-2xl font-semibold mb-6 border-b pb-2">Предпросмотр</h1>
    <form method="POST" action="{{ route('admin.import.execute') }}">
        @csrf
        <input type="hidden" name="path" value="{{ $path }}">
        <div class="mb-4">
            <label class="block font-medium mb-2">Категория</label>
            <select name="category_id" class="border rounded p-2">
                <option value="">-- новая категория --</option>
                @foreach(\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <input type="text" name="new_category" placeholder="Новая категория" class="mt-2 border rounded p-2 w-full">
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-2">Статус</label>
            <select name="status" class="border rounded p-2">
                @foreach(\App\Models\Skladchina::statuses() as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-2">Владелец</label>
            <select name="organizer_id" class="border rounded p-2">
                @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <table class="mb-4 w-full text-sm text-left">
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th class="px-2 py-1 border">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td class="border px-2 py-1">{{ Str::limit($cell, 50) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-4">
            @foreach(['name'=>'Название','description'=>'Описание','full_price'=>'Цена','member_price'=>'Взнос','images'=>'Картинки'] as $field=>$label)
                <div>
                    <label class="block text-sm font-medium">{{ $label }}</label>
                    <select name="mapping[{{ $field }}]" class="w-full border rounded p-1">
                        <option value="">Не использовать</option>
                        @foreach($headers as $header)
                            <option value="{{ $header }}">{{ $header }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Импортировать</button>
    </form>
@endsection
