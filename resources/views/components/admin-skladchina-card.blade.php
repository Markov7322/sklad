@props(['skladchina', 'preload' => false])
<div class="bg-white rounded-2xl shadow hover:shadow-lg overflow-hidden flex flex-col">
    @if($skladchina->image_path)
        <div class="w-full h-48 overflow-hidden relative group">
            <picture>
                <source media="(max-width: 640px)" srcset="{{ asset('images/400/'.$skladchina->image_path) }}">
                <img
                    src="{{ asset('images/800/'.$skladchina->image_path) }}"
                    alt="{{ $skladchina->name }}"
                    loading="{{ $preload ? 'eager' : 'lazy' }}"
                    fetchpriority="{{ $preload ? 'high' : 'auto' }}"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            </picture>
            @if($skladchina->images->first())
                <picture class="absolute inset-0 w-full h-full opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                    <source media="(max-width: 640px)" srcset="{{ asset('images/400/'.$skladchina->images->first()->path) }}">
                    <img
                        src="{{ asset('images/800/'.$skladchina->images->first()->path) }}"
                        alt=""
                        loading="lazy"
                        class="w-full h-full object-cover">
                </picture>
            @endif
        </div>
    @else
        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
            <span class="text-gray-500">Нет изображения</span>
        </div>
    @endif

    <div class="px-4 pt-3">
        <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-green-500 rounded-full">
            {{ $skladchina->status_label }}
        </span>
    </div>

    <div class="px-4 mt-2 flex-shrink">
        <h3 class="text-lg font-bold text-gray-900 leading-snug">
            <a href="{{ route('admin.skladchinas.edit', $skladchina) }}" class="hover:underline">
                {{ $skladchina->name }}
            </a>
        </h3>
    </div>

    <div class="px-4 mt-3 text-gray-700">
        <span class="text-sm font-medium">Взнос:&nbsp;</span>
        <span class="text-blue-600 font-semibold">{{ number_format($skladchina->member_price, 0, '', ' ') }} ₽</span>
        <span class="mx-2">|</span>
        <span class="text-sm font-medium">Сбор:&nbsp;</span>
        <span class="font-semibold">{{ number_format($skladchina->full_price, 0, '', ' ') }} ₽</span>
    </div>

    <div class="px-4 mt-2 text-sm text-gray-600">
        <span class="font-medium">Категория:</span>
        <span>{{ optional($skladchina->category)->name ?? '—' }}</span>
    </div>

    <div class="mt-auto px-4 pb-4 pt-4">
        @php
            $user = auth()->user();
            $isSubscribed = $user && $user->isSubscribed($skladchina);
            $hasPaid = $isSubscribed && $skladchina->participants()->where('user_id', $user->id)->first()?->pivot->paid;
        @endphp

        @if($isSubscribed)
            @if($hasPaid)
                <button disabled class="w-full bg-green-600 text-white font-semibold px-4 py-2 rounded-lg">Вы участвуете (Оплачено)</button>
            @else
                <button disabled class="w-full bg-yellow-500 text-white font-semibold px-4 py-2 rounded-lg">Вы участвуете (Не оплачено)</button>
            @endif
        @else
            <form action="{{ route('admin.skladchinas.participants', $skladchina) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-700 transition">Участвовать</button>
            </form>
        @endif
    </div>
</div>

<div class="flex justify-between mt-2 px-1">
    <a href="{{ route('admin.skladchinas.edit', $skladchina) }}" class="text-blue-500 hover:underline text-sm">Редактировать</a>
    <a href="{{ route('admin.skladchinas.participants', $skladchina) }}" class="text-blue-500 hover:underline text-sm">Участники</a>
    <form action="{{ route('admin.skladchinas.destroy', $skladchina) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-500 hover:underline text-sm" onclick="return confirm('Удалить эту складчину?')">Удалить</button>
    </form>
</div>
