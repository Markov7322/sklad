@section('title', config('app.name'))

@push('meta')
    @php
        $seoDescription = 'Список категорий и складчин на сайте ' . config('app.name');
        $firstImage = optional($categories->first()?->skladchinas?->first())->image_path;
    @endphp
    <meta name="description" content="{{ $seoDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ config('app.name') }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    @if($firstImage)
        <link rel="preload" as="image" type="image/avif"
              href="{{ asset('images/800/' . str_replace('.webp', '.avif', $firstImage)) }}"
              imagesrcset="{{ asset('images/400/' . str_replace('.webp', '.avif', $firstImage)) }} 400w, {{ asset('images/800/' . str_replace('.webp', '.avif', $firstImage)) }} 800w"
              imagesizes="(max-width: 640px) 400px, 800px"
              fetchpriority="high">
        <link rel="preload" as="image" type="image/webp"
              href="{{ asset('images/800/' . $firstImage) }}"
              imagesrcset="{{ asset('images/400/' . $firstImage) }} 400w, {{ asset('images/800/' . $firstImage) }} 800w"
              imagesizes="(max-width: 640px) 400px, 800px"
              fetchpriority="high">
    @endif
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => config('app.name'),
            'description' => $seoDescription,
            'url' => url()->current(),
        ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('breadcrumbs')
    <x-breadcrumbs :items="[['label' => 'Главная']]" />
@endsection

<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Заголовок --}}
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Категории</h1>

        @foreach($categories as $category)
            {{-- Название категории --}}
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
                <a href="{{ route('categories.show', $category->slug) }}" class="hover:underline">{{ $category->name }}</a>
            </h2>

            {{-- Сетка карточек складчин --}}
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-12">
                @foreach($category->skladchinas as $skladchina)
                    <x-home-skladchina-card :skladchina="$skladchina" :preload="$loop->parent->first && $loop->first" />
                @endforeach
            </div>
        @endforeach
    </div>
</x-app-layout>
