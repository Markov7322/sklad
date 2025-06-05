@props(['skladchina', 'user' => null])

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg overflow-hidden flex flex-col" onclick="window.location='{{ route('skladchinas.show', $skladchina) }}'">
    {{-- 1. Фото обложки --}}
    @if($skladchina->image_path)
        <div class="w-full h-48 overflow-hidden relative group">
            <img
                src="{{ asset('storage/' . $skladchina->image_path) }}"
                alt="{{ $skladchina->name }}"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
            >
            @if($skladchina->images->first())
                <img
                    src="{{ asset('storage/'.$skladchina->images->first()->path) }}"
                    alt=""
                    class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                >
            @endif
        </div>
    @else
        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
            <span class="text-gray-500 dark:text-gray-400">Нет изображения</span>
        </div>
    @endif

    {{-- 2. Статус складчины --}}
    <div class="px-4 pt-3">
        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full {{ $skladchina->status_badge_classes }}">
            {{ $skladchina->status_label }}
        </span>
    </div>

    {{-- 3. Название складчины --}}
    <div class="px-4 mt-2">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-snug">
            <a href="{{ route('skladchinas.show', $skladchina) }}" class="hover:underline" onclick="event.stopPropagation();">
                {{ $skladchina->name }}
            </a>
        </h3>
    </div>

    {{-- 4. Сумма сбора и сумма взноса в одной строке --}}
    <div class="px-4 mt-3">
        <span class="text-sm text-gray-600 dark:text-gray-300">
            <span class="font-medium">Сбор:</span>
            <span class="text-gray-900 dark:text-gray-100">{{ number_format($skladchina->full_price, 0, '', ' ') }} ₽</span>
            <span class="mx-2">|</span>
            <span class="font-medium">Взнос:</span>
            <span class="text-gray-900 dark:text-gray-100">{{ number_format($skladchina->member_price, 0, '', ' ') }} ₽</span>
        </span>
    </div>

    {{-- 5. Статус участия (участвует или нет) --}}
    <div class="px-4 mt-3">
        @auth
            @if(($user ?? auth()->user()) && ($user ?? auth()->user())->isSubscribed($skladchina))
                <span class="inline-flex items-center bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 text-sm font-semibold px-3 py-1 rounded-full">
                    Вы участвуете
                </span>
            @else
                <span class="inline-flex items-center bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 text-sm font-semibold px-3 py-1 rounded-full">
                    Вы не участвуете
                </span>
            @endif
        @else
            <span class="inline-flex items-center bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 text-sm font-semibold px-3 py-1 rounded-full">
                Не авторизованы
            </span>
        @endauth
    </div>

    {{-- 6. Кнопка “Участвовать” или “Вы участвуете” --}}
    <div class="mt-auto px-4 pb-4 pt-2" onclick="event.stopPropagation();">
        @auth
            @if(($user ?? auth()->user())->isSubscribed($skladchina))
                <button
                    disabled
                    class="w-full text-center bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold px-4 py-2 rounded-lg cursor-default"
                >
                    Вы участвуете
                </button>
            @else
                <form action="{{ route('skladchinas.join', $skladchina) }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="w-full bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white dark:text-gray-100 font-semibold px-4 py-2 rounded-lg transition"
                    >
                        Участвовать
                    </button>
                </form>
            @endif
        @else
            <a
                href="{{ route('login') }}"
                class="w-full block text-center bg-gray-600 dark:bg-gray-500 hover:bg-gray-700 dark:hover:bg-gray-400 text-white dark:text-gray-100 font-semibold px-4 py-2 rounded-lg transition"
            >
                Войдите, чтобы участвовать
            </a>
        @endauth
    </div>
</div>
