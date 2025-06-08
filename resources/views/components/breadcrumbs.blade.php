@props(['items', 'class' => 'hidden'])

@if(!empty($items))
<nav aria-label="breadcrumb" class="{{ $class }}">
    <ol class="flex flex-wrap items-center text-sm" itemscope itemtype="https://schema.org/BreadcrumbList">
        @foreach($items as $index => $item)
            @if($index > 0)
                <li aria-hidden="true" class="mx-2 text-gray-400">&#8250;</li>
            @endif
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="flex items-center">
                @if(!empty($item['url']))
                    <a itemprop="item" href="{{ $item['url'] }}" class="text-blue-600 hover:underline">
                        <span itemprop="name">{{ $item['label'] }}</span>
                    </a>
                @else
                    <span itemprop="name">{{ $item['label'] }}</span>
                    <meta itemprop="item" content="{{ url()->current() }}" />
                @endif
                <meta itemprop="position" content="{{ $index + 1 }}" />
            </li>
        @endforeach
    </ol>
</nav>
@endif
