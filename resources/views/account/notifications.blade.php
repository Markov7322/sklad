<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Push-уведомления</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('account.notifications.update') }}" class="space-y-4">
                    @csrf
                    <label class="flex items-center justify-between">
                        <span>Статусы складчин</span>
                        <input type="checkbox" name="notify_status_changes" value="1" class="toggle" @checked(auth()->user()->notify_status_changes)>
                    </label>
                    <label class="flex items-center justify-between">
                        <span>Уведомления сайта</span>
                        <input type="checkbox" name="notify_site" value="1" class="toggle" @checked(auth()->user()->notify_site)>
                    </label>
                    <label class="flex items-center justify-between">
                        <span>Движение баланса</span>
                        <input type="checkbox" name="notify_balance_changes" value="1" class="toggle" @checked(auth()->user()->notify_balance_changes)>
                    </label>
                    <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
