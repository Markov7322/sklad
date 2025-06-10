@push('meta')
    <meta name="description" content="{{ $seo['description'] ?? '' }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seo['title'] ?? '' }}">
    <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
    @if(!empty($seo['image']))
        <meta property="og:image" content="{{ asset('images/800/'.str_replace('.webp', '.avif', $seo['image'])) }}">
        <link rel="preload" as="image" type="image/avif"
              href="{{ asset('images/800/'.str_replace('.webp', '.avif', $seo['image'])) }}"
              imagesrcset="{{ asset('images/400/'.str_replace('.webp', '.avif', $seo['image'])) }} 400w, {{ asset('images/800/'.str_replace('.webp', '.avif', $seo['image'])) }} 800w"
              imagesizes="(max-width: 640px) 400px, 800px"
              fetchpriority="high">
        <link rel="preload" as="image" type="image/webp"
              href="{{ asset('images/800/'.$seo['image']) }}"
              imagesrcset="{{ asset('images/400/'.$seo['image']) }} 400w, {{ asset('images/800/'.$seo['image']) }} 800w"
              imagesizes="(max-width: 640px) 400px, 800px"
              fetchpriority="high">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="{{ asset('images/800/'.str_replace('.webp', '.avif', $seo['image'])) }}">
    @else
        <meta name="twitter:card" content="summary">
    @endif
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="product">
    <meta name="twitter:title" content="{{ $seo['title'] ?? '' }}">
    <meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
    @if(!empty($seo['jsonLd']))
        <script type="application/ld+json">{!! $seo['jsonLd'] !!}</script>
    @endif
@endpush
