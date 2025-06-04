<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Заголовок --}}
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Категории</h1>

        @foreach($categories as $category)
            {{-- Название категории --}}
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
                {{ $category->name }}
            </h2>

            {{-- Сетка карточек складчин --}}
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-12">
                @foreach($category->skladchinas as $skladchina)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-200">
                        {{-- Обложка --}}
                        @if($skladchina->image_path)
                            <div class="w-full h-48 overflow-hidden">
                                <img
                                    src="{{ asset('storage/'.$skladchina->image_path) }}"
                                    alt="{{ $skladchina->name }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                                >
                            </div>
                        @else
                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-500 dark:text-gray-400">Изображение отсутствует</span>
                            </div>
                        @endif

                        {{-- Контент карточки --}}
                        <div class="p-5 flex flex-col justify-between h-full">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 rounded-full mb-2 {{ $skladchina->status_badge_classes }}">{{ $skladchina->status_label }}</span>
                            {{-- Заголовок --}}
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                {{ $skladchina->name }}
                            </h3>

                            {{-- Краткое описание --}}
                            @if(!empty($skladchina->description))
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 leading-relaxed">
                                    {{ Str::limit($skladchina->description, 80) }}
                                </p>
                            @endif

                            {{-- Цены --}}
                            <div class="flex justify-between items-baseline mb-4">
                                <span class="text-blue-600 dark:text-blue-300 font-semibold">
                                    {{ number_format($skladchina->full_price, 0, '', ' ') }} ₽
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    Взнос: {{ number_format($skladchina->member_price, 0, '', ' ') }} ₽
                                </span>
                            </div>

                            {{-- Кнопка или метка участия --}}
                            <div>
                                @auth
                                    @if(! $skladchina->participants->contains(Auth::id()))
                                        <form action="{{ route('skladchinas.join', $skladchina) }}" method="POST">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="w-full inline-flex justify-center items-center bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white dark:text-gray-100 font-medium px-4 py-2 rounded-lg shadow-md transition duration-200"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                  <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
                                                </svg>
                                                Записаться
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm font-semibold px-4 py-2 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 10-1.414 1.414L9 13.414l4.707-4.707z" clip-rule="evenodd" />
                                            </svg>
                                            Вы участвуете
                                        </span>
                                    @endif
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="w-full inline-flex justify-center items-center bg-gray-600 dark:bg-gray-500 hover:bg-gray-700 dark:hover:bg-gray-400 text-white dark:text-gray-100 font-medium px-4 py-2 rounded-lg shadow-md transition duration-200"
                                    >
                                        Войдите, чтобы записаться
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</x-app-layout>
