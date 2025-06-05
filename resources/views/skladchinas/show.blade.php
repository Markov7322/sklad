<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-10">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            @if($skladchina->image_path)
                <div class="w-full h-64 sm:h-80 lg:h-96 overflow-hidden">
                    <img src="{{ asset('storage/'.$skladchina->image_path) }}" alt="{{ $skladchina->name }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                </div>
            @else
                <div class="w-full h-64 sm:h-80 lg:h-96 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="text-gray-500 dark:text-gray-400 text-lg">Изображение отсутствует</span>
                </div>
            @endif

            <div class="px-6 py-8">
                @if($skladchina->category)
                    <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm font-semibold px-3 py-1 rounded-full mb-3">
                        {{ $skladchina->category->name }}
                    </span>
                @endif
                <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 rounded-full mb-3 {{ $skladchina->status_badge_classes }}">{{ $skladchina->status_label }}</span>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">
                    {{ $skladchina->name }}
                </h1>

                @if($skladchina->description)
                    <p class="mt-4 text-gray-700 dark:text-gray-300 leading-relaxed">
                        {{ $skladchina->description }}
                    </p>
                @endif

                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-start sm:space-x-8">
                    <div class="flex items-baseline">
                        <span class="text-gray-500 dark:text-gray-400 text-sm">Полная цена:</span>
                        <span class="ml-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($skladchina->full_price, 0, '', ' ') }} ₽</span>
                    </div>
                    <div class="mt-4 sm:mt-0 flex items-baseline">
                        <span class="text-gray-500 dark:text-gray-400 text-sm">Взнос:</span>
                        <span class="ml-2 text-2xl font-semibold text-blue-600 dark:text-blue-300">{{ number_format($skladchina->member_price, 0, '', ' ') }} ₽</span>
                    </div>
                </div>

                <div class="mt-8">
                    @php
                        $participant = auth()->check() ? $skladchina->participants->where('id', auth()->id())->first() : null;
                    @endphp
                    @if($participant)
                        <span class="inline-flex items-center bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm font-semibold px-4 py-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 10-1.414 1.414L9 13.414l4.707-4.707z" clip-rule="evenodd" />
                            </svg>
                            Вы участвуете - {{ $participant->pivot->paid ? 'оплачено' : 'не оплачено' }}
                        </span>
                        @if($participant->pivot->paid)
                            @if($skladchina->attachment && in_array($skladchina->status, [\App\Models\Skladchina::STATUS_ISSUE, \App\Models\Skladchina::STATUS_AVAILABLE]) && (! $participant->pivot->access_until || now()->lte($participant->pivot->access_until)))
                                <div class="mt-4">
                                    <a href="{{ $skladchina->attachment }}" class="inline-flex items-center bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white dark:text-gray-100 font-medium px-6 py-3 rounded-lg shadow-md transition" target="_blank">Ссылка на облако</a>
                                </div>
                            @elseif($participant->pivot->access_until && now()->gt($participant->pivot->access_until))
                                <form action="{{ route('skladchinas.renew', $skladchina) }}" method="POST" class="mt-4 inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center bg-purple-600 dark:bg-purple-500 hover:bg-purple-700 dark:hover:bg-purple-400 text-white dark:text-gray-100 font-medium px-6 py-3 rounded-lg shadow-md transition">Продлить за {{ number_format($skladchina->member_price * 0.4, 0, '', ' ') }} ₽</button>
                                </form>
                            @endif
                        @else
                            <form action="{{ route('skladchinas.pay', $skladchina) }}" method="POST" class="mt-4 inline-block">
                                @csrf
                                <button type="submit" class="inline-flex items-center bg-green-600 dark:bg-green-500 hover:bg-green-700 dark:hover:bg-green-400 text-white dark:text-gray-100 font-medium px-6 py-3 rounded-lg shadow-md transition">Оплатить с баланса</button>
                            </form>
                        @endif
                    @else
                        @auth
                            <form action="{{ route('skladchinas.join', $skladchina) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="inline-flex items-center bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white dark:text-gray-100 font-medium px-6 py-3 rounded-lg shadow-md transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                      <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
                                    </svg>
                                    Записаться
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center bg-gray-600 dark:bg-gray-500 hover:bg-gray-700 dark:hover:bg-gray-400 text-white dark:text-gray-100 font-medium px-6 py-3 rounded-lg shadow-md transition">
                                Войдите, чтобы записаться
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
