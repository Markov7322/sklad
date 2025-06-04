<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Пользователи</h1>
    <ul class="mt-4">
        @foreach($users as $user)
            <li class="mb-2">
                {{ $user->name }} ({{ $user->email }}) - {{ $user->role }}
                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-500 ml-2">Редактировать</a>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 ml-2">Удалить</button>
                </form>
            </li>
        @endforeach
    </ul>
</x-app-layout>
