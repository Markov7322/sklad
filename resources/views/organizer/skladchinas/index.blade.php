<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Мои складчины</h1>
            <a href="{{ route('skladchinas.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white dark:text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                + Создать новую
            </a>
        </div>

        @if($skladchinas->isEmpty())
            <div class="text-gray-500 dark:text-gray-300">
                Вы ещё не создали ни одной складчины.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($skladchinas as $skladchina)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg transition">
                        <x-skladchina-card :skladchina="$skladchina" :user="auth()->user()" />
                        <div class="border-t px-4 py-3 text-center">
                            <a href="{{ route('skladchinas.edit', $skladchina) }}"
                               class="inline-block text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                ✏️ Редактировать
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
