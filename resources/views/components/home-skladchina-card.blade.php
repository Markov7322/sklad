@props(['skladchina', 'user' => null])

@php
    // Текущий пользователь (если не передан явно в компонент, берём auth)
    $currentUser = $user ?? auth()->user();

    // Если пользователь авторизован - пытаемся получить запись (pivot) этого юзера в складчине
    $participant = null;
    if ($currentUser) {
        $participant = $skladchina->participants->first(function($u) use ($currentUser) {
            return $u->id === $currentUser->id;
        });
    }

    // Статус оплаты (null - не записан, false - записан но не оплатил, true - оплатил)
    $paidStatus = null;
    if ($participant) {
        // Предполагается, что в pivot-е есть булево поле 'paid'
        $paidStatus = (bool) $participant->pivot->paid;
    }
@endphp

{{-- Карточка целиком кликабельна --}}
<div
    class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg overflow-hidden flex flex-col"
    onclick="window.location='{{ route('skladchinas.show', $skladchina) }}'"
>
    {{-- 1. Фото обложки --}}
    @if($skladchina->image_path)
        <div class="w-full h-48 overflow-hidden relative group">
            <picture>
                <source media="(max-width: 640px)" srcset="{{ asset('images/400/' . $skladchina->image_path) }}">
                <img
                    src="{{ asset('images/800/' . $skladchina->image_path) }}"
                    alt="{{ $skladchina->title }}"
                    fetchpriority="high"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                >
            </picture>
            @if($skladchina->images->first())
                <picture class="absolute inset-0 w-full h-full opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                    <source media="(max-width: 640px)" srcset="{{ asset('images/400/'.$skladchina->images->first()->path) }}">
                    <img
                        src="{{ asset('images/800/'.$skladchina->images->first()->path) }}"
                        alt=""
                        loading="lazy"
                        class="w-full h-full object-cover"
                    >
                </picture>
            @endif
        </div>
    @else
        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
            <span class="text-gray-500 dark:text-gray-400">Нет изображения</span>
        </div>
    @endif

    {{-- 2. Контент карточки: статус, название, цены --}}
    <div class="flex-1 flex flex-col px-4 pt-3 pb-2">
        {{-- 2.1 Статус складчины --}}
        <div class="mb-2">
            <span
                class="inline-block px-3 py-1 text-sm font-semibold rounded-full {{ $skladchina->status_badge_classes }}"
            >
                {{ $skladchina->status_label }}
            </span>
        </div>

        {{-- 2.2 Название --}}
        <div class="mb-2 flex-1">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-snug">
                <a
                    href="{{ route('skladchinas.show', $skladchina) }}"
                    class="hover:underline"
                    onclick="event.stopPropagation();"
                >
                    {{ $skladchina->title }}
                </a>
            </h3>
        </div>

        {{-- 2.3 Цены: сначала "Взнос", затем "Сбор" --}}
        <div class="mb-2 text-sm text-gray-600 dark:text-gray-300">
            <span class="font-medium">Взнос:</span>
            <span class="text-gray-900 dark:text-gray-100">
                {{ number_format($skladchina->member_price, 0, '', ' ') }} ₽
            </span>
            <span class="mx-1">|</span>
            <span class="font-medium">Сбор:</span>
            <span class="text-gray-900 dark:text-gray-100">
                {{ number_format($skladchina->full_price, 0, '', ' ') }} ₽
            </span>
        </div>
    </div>

    {{-- 3. Нижняя часть карточки: кнопка участия или информация об оплате --}}
    <div class="px-4 pb-4">
        @auth
            {{-- 3.1 Пользователь не записан --}}
            @if( is_null($participant) )
                <form action="{{ route('skladchinas.join', $skladchina) }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="w-full bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white dark:text-gray-100 font-semibold px-4 py-2 rounded-lg transition"
                        onclick="event.stopPropagation();"
                    >
                        Участвовать
                    </button>
                </form>
            @else
                {{-- Пользователь записан: смотрим paidStatus --}}
                @if( $paidStatus === false )
                    {{-- Записан, но не оплатил --}}
                    <button
                        disabled
                        class="w-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 font-semibold px-4 py-2 rounded-lg cursor-default"
                        onclick="event.stopPropagation();"
                    >
                        Вы участвуете (Не оплачено)
                    </button>
                @else
                    {{-- Записан и оплатил --}}
                    <button
                        disabled
                        class="w-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 font-semibold px-4 py-2 rounded-lg cursor-default"
                        onclick="event.stopPropagation();"
                    >
                        Вы участвуете (Оплачено)
                    </button>
                @endif
            @endif
        @else
            {{-- 3.2 Неавторизованный --}}
            <a
                href="{{ route('login') }}"
                class="w-full block text-center bg-gray-600 dark:bg-gray-500 hover:bg-gray-700 dark:hover:bg-gray-400 text-white dark:text-gray-100 font-semibold px-4 py-2 rounded-lg transition"
                onclick="event.stopPropagation();"
            >
                Войдите, чтобы участвовать
            </a>
        @endauth
    </div>
</div>
