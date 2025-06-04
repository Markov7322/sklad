@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6 border-b pb-2">Редактировать пользователя</h1>
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded p-2" />
        <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded p-2" />
        <input type="password" name="password" placeholder="Новый пароль" class="w-full border rounded p-2" />
        <select name="role" class="w-full border rounded p-2">
            <option value="user" @selected($user->role==='user')>user</option>
            <option value="moderator" @selected($user->role==='moderator')>moderator</option>
            <option value="admin" @selected($user->role==='admin')>admin</option>
        </select>
        <x-primary-button class="mt-2">Сохранить</x-primary-button>
    </form>
@endsection
