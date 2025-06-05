@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6 border-b pb-2">Импорт складчин</h1>
    <form method="POST" action="{{ route('admin.import.preview') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block font-medium">Файл Excel</label>
            <input type="file" name="file" required class="mt-1">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Загрузить</button>
    </form>
@endsection
