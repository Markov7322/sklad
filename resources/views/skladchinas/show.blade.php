{{-- resources/views/skladchinas/show.blade.php --}}
<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-10">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">

            @php
                $gallery = collect();
                if ($skladchina->image_path) {
                    $gallery->push($skladchina->image_path);
                }
                foreach ($skladchina->images as $img) {
                    $gallery->push($img->path);
                }
            @endphp

            {{-- ГАЛЕРЕЯ --}}
            <div class="w-full" 
                 x-data="{ index: 0, images: {{ $gallery->toJson() }} }">
                <div class="relative w-full h-80 sm:h-96 lg:h-[28rem] bg-gray-100 dark:bg-gray-700">
                    <template x-for="(img, i) in images" :key="i">
                        <img
                            x-show="index === i"
                            :src="'/storage/' + img"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                            x-transition.opacity
                        >
                    </template>

                    <button
                        @click="index = (index === 0 ? images.length - 1 : index - 1)"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 rounded-full p-2 hover:bg-white dark:hover:bg-gray-700 transition"
                        x-show="images.length > 1"
                        x-transition.opacity
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button
                        @click="index = (index === images.length - 1 ? 0 : index + 1)"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 rounded-full p-2 hover:bg-white dark:hover:bg-gray-700 transition"
                        x-show="images.length > 1"
                        x-transition.opacity
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <div class="flex justify-center gap-2 mt-3 overflow-x-auto px-2" x-show="images.length > 1">
                    <template x-for="(img, i) in images" :key="'thumb-'+i">
                        <div class="shrink-0">
                            <img
                                @click="index = i"
                                :src="'/storage/' + img"
                                class="w-16 h-16 object-cover rounded-lg cursor-pointer border-2 transition
                                    "
                                :class="index === i 
                                    ? 'border-blue-500 ring-2 ring-blue-300 dark:ring-blue-600' 
                                    : 'border-transparent hover:ring-1 hover:ring-gray-400/50 dark:hover:ring-gray-200/30'"
                            >
                        </div>
                    </template>
                </div>
            </div>

            <div class="px-6 py-8" x-data="{ openDesc: false }">

                {{-- Категория и Статус --}}
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    @if($skladchina->category)
                        <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm font-semibold px-3 py-1 rounded-full">
                            {{ $skladchina->category->name }}
                        </span>
                    @endif

                    <span class="inline-block {{ $skladchina->status_badge_classes }} text-sm font-semibold px-3 py-1 rounded-full">
                        {{ $skladchina->status_label }}
                    </span>
                </div>

                {{-- Название --}}
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white mb-2">
                    {{ $skladchina->name }}
                </h1>

                {{-- Взнос и Полная цена --}}
                <div class="flex flex-col sm:flex-row sm:items-start sm:space-x-8 mb-6">
                    {{-- Взнос --}}
                    <div class="flex items-baseline mb-4 sm:mb-0">
                        <span class="text-gray-500 dark:text-gray-400 text-sm">Взнос:</span>
                        <span class="ml-2 text-2xl font-semibold text-blue-600 dark:text-blue-300">
                            {{ number_format($skladchina->member_price, 0, '', ' ') }} ₽
                        </span>
                    </div>
                    {{-- Полная цена --}}
                    <div class="flex items-baseline">
                        <span class="text-gray-500 dark:text-gray-400 text-sm">Полная цена:</span>
                        <span class="ml-2 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($skladchina->full_price, 0, '', ' ') }} ₽
                        </span>
                    </div>
                </div>

                {{-- Участие / Оплата --}}
                <div class="mb-6">
                    @php
                        $participant = auth()->check() 
                            ? $skladchina->participants->where('id', auth()->id())->first() 
                            : null;
                    @endphp

                    @if($participant)
                        <div class="flex flex-wrap items-center gap-3">
                            {{-- Бейдж «Вы участвуете» --}}
                            <span class="inline-flex items-center bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm font-semibold px-4 py-2 rounded-full max-w-max">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 10-1.414 1.414L9 13.414l4.707-4.707z" clip-rule="evenodd" />
                                </svg>
                                Вы участвуете — {{ $participant->pivot->paid ? 'оплачено' : 'не оплачено' }}
                            </span>

                            {{-- Если не оплачено — кнопка «Оплатить» --}}
                            @if(!$participant->pivot->paid)
                                <form action="{{ route('skladchinas.pay', $skladchina) }}" method="POST" class="mt-2 sm:mt-0">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center bg-green-600 dark:bg-green-500 hover:bg-green-700 dark:hover:bg-green-400 text-white dark:text-gray-100 font-medium px-6 py-3 rounded-lg shadow-md transition">
                                        Оплатить с баланса
                                    </button>
                                </form>
                            @elseif(
                                $skladchina->attachment 
                                && in_array($skladchina->status, [\App\Models\Skladchina::STATUS_ISSUE, \App\Models\Skladchina::STATUS_AVAILABLE]) 
                                && (! $participant->pivot->access_until || now()->lte($participant->pivot->access_until))
                            )
                                {{-- Ссылка на облако --}}
                                <div class="mt-2 sm:mt-0">
                                    <a href="{{ $skladchina->attachment }}" target="_blank"
                                       class="inline-flex items-center bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white dark:text-gray-100 font-medium px-6 py-3 rounded-lg shadow-md transition">
                                        Ссылка на облако
                                    </a>
                                </div>
                            @elseif($participant->pivot->access_until && now()->gt($participant->pivot->access_until))
                                {{-- Кнопка «Продлить» --}}
                                <form action="{{ route('skladchinas.renew', $skladchina) }}" method="POST" class="mt-2 sm:mt-0">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center bg-purple-600 dark:bg-purple-500 hover:bg-purple-700 dark:hover:bg-purple-400 text-white dark:text-gray-100 font-medium px-6 py-3 rounded-lg shadow-md transition">
                                        Продлить за {{ number_format($skladchina->member_price * 0.4, 0, '', ' ') }} ₽
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        @auth
                            {{-- Если не участвует — «Записаться» --}}
                            <form action="{{ route('skladchinas.join', $skladchina) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white dark:text-gray-100 font-medium px-6 py-3 rounded-lg shadow-md transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                      <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
                                    </svg>
                                    Записаться
                                </button>
                            </form>
                        @else
                            {{-- Если не авторизован --}}
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center bg-gray-600 dark:bg-gray-500 hover:bg-gray-700 dark:hover:bg-gray-400 text-white dark:text-gray-100 font-medium px-6 py-3 rounded-lg shadow-md transition">
                                Войдите, чтобы записаться
                            </a>
                        @endauth
                    @endif
                </div>

                {{-- ОПИСАНИЕ --}}
                @if($skladchina->description)
                    <div class="relative mb-6">
                        <div 
                            :class="openDesc ? 'max-h-full overflow-visible' : 'max-h-24 overflow-hidden'"
                            class="text-gray-700 dark:text-gray-300 leading-relaxed transition-all duration-300"
                        >
                            {!! $skladchina->description !!}
                        </div>
                        <div 
                            x-show="!openDesc" 
                            class="absolute bottom-0 left-0 w-full h-16 bg-gradient-to-t from-white dark:from-gray-800 to-transparent pointer-events-none"
                        ></div>
                        <button
                            @click="openDesc = !openDesc"
                            class="mt-2 text-blue-600 dark:text-blue-400 text-sm font-semibold hover:underline"
                        >
                            <span x-text="openDesc ? 'Свернуть описание' : 'Показать полное описание'"></span>
                        </button>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
