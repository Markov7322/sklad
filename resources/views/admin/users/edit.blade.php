<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Редактировать пользователя</h1>
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $user->name }}" class="border p-2 block mb-2" />
        <input type="email" name="email" value="{{ $user->email }}" class="border p-2 block mb-2" />
        <input type="password" name="password" placeholder="Новый пароль" class="border p-2 block mb-2" />
        <select name="role" class="border p-2 block mb-2">
            <option value="user" @selected($user->role==='user')>user</option>
            <option value="moderator" @selected($user->role==='moderator')>moderator</option>
            <option value="admin" @selected($user->role==='admin')>admin</option>
        </select>
        <x-primary-button>Сохранить</x-primary-button>
    </form>
</x-app-layout>
