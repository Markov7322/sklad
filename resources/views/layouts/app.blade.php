<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    @stack('meta')

    <!-- Tailwind CSS (через Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col">

        {{-- ====== ШАПКА (Header) ====== --}}
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">

                    {{-- 1) Логотип + название --}}
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <x-application-logo class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                            <span class="ml-2 font-bold text-xl text-gray-800 dark:text-white">
                                {{ config('app.name') }}
                            </span>
                        </a>
                    </div>

                    {{-- 2) Основная навигация --}}
                    <nav class="hidden md:flex space-x-6">
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">Главная</x-nav-link>
                        <x-nav-link :href="route('skladchinas.index')" :active="request()->routeIs('skladchinas.*')">Каталог</x-nav-link>
                        @auth
                            <x-nav-link :href="route('account.balance')" :active="request()->routeIs('account.balance')">Баланс</x-nav-link>
                            <x-nav-link :href="route('account.participations')" :active="request()->routeIs('account.participations')">Мои складчины</x-nav-link>
                            @if(in_array(Auth::user()->role, ['admin','moderator'], true))
                                <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">Категории</x-nav-link>
                            @endif
                            @if(in_array(Auth::user()->role, ['admin','moderator'], true))
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">Админ</x-nav-link>
                            @endif
                        @endauth
                        <x-nav-link :href="route('about')" :active="request()->routeIs('about')">О проекте</x-nav-link>
                        <x-nav-link :href="route('contacts')" :active="request()->routeIs('contacts')">Контакты</x-nav-link>
                    </nav>

                    {{-- 3) Правый блок: поиск, тема, пользователь, бургер --}}
                    <div class="flex items-center space-x-4">

                        {{-- 3.0) Поиск --}}
                        <form method="GET" action="{{ route('skladchinas.index') }}" class="hidden md:block relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="pl-3 pr-10 py-2 w-48 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Поиск...">
                            <button type="submit" aria-label="Поиск" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 12.65z" />
                                </svg>
                            </button>
                        </form>

                        {{-- 3.1) Переключатель светлая/тёмная тема --}}
                        <button id="theme-toggle" type="button" aria-label="Переключить тему"
                                class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none">
                            <svg id="theme-toggle-light-icon" class="w-6 h-6 transition-opacity opacity-100 dark:opacity-0" fill="none" stroke="currentColor" stroke-width="1.5"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 3v1.5M12 19.5V21M4.22 4.22l1.06 1.06M17.72 17.72l1.06 1.06M3 12h1.5M19.5 12H21M4.22 19.78l1.06-1.06M17.72 6.28l1.06-1.06M12 7.5A4.5 4.5 0 1112 16.5a4.5 4.5 0 010-9z"/>
                            </svg>
                            <svg id="theme-toggle-dark-icon" class="w-6 h-6 transition-opacity opacity-0 dark:opacity-100" fill="none" stroke="currentColor" stroke-width="1.5"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z"/>
                            </svg>
                        </button>
						
                        {{-- 3.2) Пользовательский дропдаун или ссылки «Войти/Регистрация» --}}
                        @auth
                            <div class="relative">
                                <button id="user-menu-button" type="button"
                                        class="flex items-center text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white focus:outline-none">
                                    <span class="sr-only">Открыть меню профиля</span>
                                    <svg class="w-6 h-6 rounded-full ring-2 ring-gray-300 dark:ring-gray-600"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M10 4a6 6 0 016 6 6 6 0 01-6 6 6 6 0 110-12zm0 2a4 4 0 100 8 4 4 0 000-8zm-3 8a3.001 3.001 0 015.83 0H7z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div id="user-dropdown"
                                     class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-20">
                                    <x-dropdown-link :href="route('profile.edit')">Профиль</x-dropdown-link>
                                    @if(Auth::user()->role === 'admin')
                                        <x-dropdown-link :href="route('admin.settings.edit')">Настройки</x-dropdown-link>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                            Выйти
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="space-x-4">
                                <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:underline">Войти</a>
                                <a href="{{ route('register') }}" class="ml-4 text-blue-600 dark:text-blue-400 hover:underline font-medium">Регистрация</a>
                            </div>
                        @endauth

                        {{-- 3.3) Бургер для мобильного меню --}}
                        <button id="mobile-menu-button" type="button" aria-label="Открыть меню"
                                class="md:hidden text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- 4) Мобильное меню (скрыто по умолчанию) --}}
            <nav id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-800">
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">Главная</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('skladchinas.index')" :active="request()->routeIs('skladchinas.*')">Каталог</x-responsive-nav-link>
                @auth
                    <x-responsive-nav-link :href="route('account.balance')" :active="request()->routeIs('account.balance')">Баланс</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('account.participations')" :active="request()->routeIs('account.participations')">Мои складчины</x-responsive-nav-link>
                    @if(in_array(Auth::user()->role, ['admin','moderator'], true))
                        <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">Категории</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">Админ</x-responsive-nav-link>
                    @endif
                @endauth
                <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')">О проекте</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('contacts')" :active="request()->routeIs('contacts')">Контакты</x-responsive-nav-link>

                @auth
                    <x-responsive-nav-link :href="route('profile.edit')">Профиль</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Выйти
                        </button>
                    </form>
                @else
                    <x-responsive-nav-link :href="route('login')">Войти</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">Регистрация</x-responsive-nav-link>
                @endauth
            </nav>
        </header>

        @hasSection('breadcrumbs')
            @yield('breadcrumbs')
        @elseif(isset($autoBreadcrumbs))
            <x-breadcrumbs :items="$autoBreadcrumbs" />
        @endif

        {{-- ====== ПОДШАПОЧНАЯ ПАНЕЛЬ (Sub-header / Panel) ====== --}}
        <div class="bg-gray-50 dark:bg-gray-900 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                {{-- Справа: категории складчин --}}
                <nav class="flex overflow-x-auto space-x-4">
                    @foreach($headerCategories as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}" class="whitespace-nowrap text-gray-700 dark:text-gray-300 hover:underline">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- ====== Основной контент страницы ====== --}}
        <main class="flex-1">
            {{ $slot }}
        </main>

        {{-- ====== ФУТЕР ====== --}}
        <footer class="bg-white dark:bg-gray-800 shadow-inner mt-auto">
            <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                © {{ date('Y') }} {{ config('app.name') }}. Все права защищены.
            </div>
        </footer>
    </div>

    <!-- Alpine.js is included in the bundled app.js -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // 1) Мобильное меню
            document.getElementById('mobile-menu-button')?.addEventListener('click', function () {
                document.getElementById('mobile-menu')?.classList.toggle('hidden');
            });

            // 2) Дропдаун пользователя
            document.getElementById('user-menu-button')?.addEventListener('click', function () {
                document.getElementById('user-dropdown')?.classList.toggle('hidden');
            });

            // 3) Переключатель темы (светлая ↔ тёмная)
            const toggleBtn = document.getElementById('theme-toggle');

            if (!toggleBtn) return;

            const applyTheme = (isDark) => {
                document.documentElement.classList.toggle('dark', isDark);
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            };

            const storedTheme    = localStorage.getItem('theme');
            const prefersDark    = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const initialIsDark  = storedTheme === 'dark' || (!storedTheme && prefersDark);

            applyTheme(initialIsDark);

            toggleBtn.addEventListener('click', () => {
                const isCurrentlyDark = document.documentElement.classList.contains('dark');
                applyTheme(!isCurrentlyDark);
            });

        });
    </script>
</body>
</html>
