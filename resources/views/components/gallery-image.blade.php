@props([
  'src',
  'index',
  'isFirst' => false,
  'alt' => '',
  'imgId' => null,
  'mobileId' => null,
])
<picture x-show="index === {{ $index }}"
         x-transition.opacity
         class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500">
    <source @if($mobileId) id="{{ $mobileId }}" @endif
      srcset="{{ asset('images/300/'.str_replace('.webp', '.avif', $src)) }} 300w, {{ asset('images/600/'.str_replace('.webp', '.avif', $src)) }} 600w, {{ asset('images/800/'.str_replace('.webp', '.avif', $src)) }} 800w, {{ asset('images/1600/'.str_replace('.webp', '.avif', $src)) }} 1600w"
      sizes="(max-width:768px) 100vw, 800px"
      type="image/avif">
    <source
      srcset="{{ asset('images/300/'.$src) }} 300w, {{ asset('images/600/'.$src) }} 600w, {{ asset('images/800/'.$src) }} 800w, {{ asset('images/1600/'.$src) }} 1600w"
      sizes="(max-width:768px) 100vw, 800px"
      type="image/webp">
    <img @if($imgId) id="{{ $imgId }}" @endif src="{{ asset('images/800/'.$src) }}"
         alt="{{ $alt }} — Фото {{ $index + 1 }}"
         width="800" height="600"
         loading="{{ $isFirst ? 'eager' : 'lazy' }}"
         fetchpriority="{{ $isFirst ? 'high' : 'auto' }}"
         class="w-full h-full object-cover">
</picture>
