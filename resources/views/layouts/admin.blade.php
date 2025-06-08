<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('admin.dashboard') }}" class="text-lg font-semibold text-gray-800 dark:text-gray-200">Admin</a>
                        <div class="hidden md:flex space-x-6">
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Dashboard</a>
                            <a href="{{ route('admin.skladchinas.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Складчины</a>
                            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Категории</a>
                            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Пользователи</a>
                            <a href="{{ route('admin.topups.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Пополнения</a>
                            <a href="{{ route('admin.import.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Импорт</a>
                            <a href="{{ route('admin.settings.edit') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Настройки</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <main class="flex-1 p-6">
            <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
