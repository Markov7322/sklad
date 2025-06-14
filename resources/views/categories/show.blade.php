@section('title', $category->name)

@push('meta')
    @php
        use Illuminate\Support\Str;
        $seoDescription = Str::limit(strip_tags($category->description ?? ''), 160);
    @endphp
    <meta name="description" content="{{ $seoDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="{{ $category->name }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $category->name }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $category->name,
            'description' => $seoDescription,
            'url' => url()->current(),
        ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@php
    $crumbs = [
        ['url' => route('home'), 'label' => 'Главная'],
        ['url' => route('skladchinas.index'), 'label' => 'Каталог'],
        ['label' => $category->name],
    ];
@endphp

@section('breadcrumbs')
    <x-breadcrumbs :items="$crumbs" />
@endsection

<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white text-center sm:flex-grow">{{ $category->name }}</h1>

            <div class="flex justify-center sm:justify-end items-center space-x-2">
                @php $toggleView = $viewMode === 'cards' ? 'table' : 'cards'; @endphp
                <a href="{{ route('categories.show', ['category' => $category->slug, 'view' => $toggleView, 'status' => request('status')]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    @if($viewMode === 'cards')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 3h14v2H3V3zm0 4h7v2H3V7zm0 4h14v2H3v-2zm0 4h7v2H3v-2z" />
                        </svg>
                        Показать таблицей
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 3h4v4H4V3zm6 0h4v4h-4V3zm-6 6h4v4H4V9zm6 0h4v4h-4V9zm-6 6h4v4H4v-4zm6 6h4v4h-4v-4z" />
                        </svg>
                        Показать карточками
                    @endif
                </a>

                <form method="GET" action="{{ route('categories.show', $category->slug) }}" class="flex items-center space-x-2">
                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 border rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        <option value="">Все статусы</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="view" value="{{ $viewMode }}" />
                </form>
            </div>
        </div>

        @if($viewMode === 'cards')
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($skladchinas as $skladchina)
                    <x-category-skladchina-card :skladchina="$skladchina" :preload="$loop->first" />
                @endforeach
            </div>
        @else
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="hidden sm:table-header-group bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Название</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Взнос</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Сбор</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Статус</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($skladchinas as $index => $skladchina)
                            <tr class="block sm:table-row bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <a href="{{ route('skladchinas.show', $skladchina) }}" class="hover:underline">{{ $skladchina->name }}</a>
                                </td>
                                <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm text-blue-600">{{ number_format($skladchina->member_price, 0, '', ' ') }} ₽</td>
                                <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($skladchina->full_price, 0, '', ' ') }} ₽</td>
                                <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-block px-2 py-1 text-xs rounded-full {{ $skladchina->status_badge_classes }}">{{ $skladchina->status_label }}</span>
                                </td>

                                <td class="sm:hidden px-6 py-4">
                                    <div class="text-sm font-semibold mb-1">
                                        <a href="{{ route('skladchinas.show', $skladchina) }}" class="hover:underline break-words">{{ $skladchina->name }}</a>
                                    </div>
                                    <div class="flex flex-nowrap items-center gap-x-2 text-xs text-gray-600 dark:text-gray-300">
                                        <span class="whitespace-nowrap"><span class="font-medium">Взнос:</span> {{ number_format($skladchina->member_price, 0, '', ' ') }} ₽</span>
                                        <span class="whitespace-nowrap"><span class="font-medium">Сбор:</span> {{ number_format($skladchina->full_price, 0, '', ' ') }} ₽</span>
                                        <span class="whitespace-nowrap">
                                            <span class="inline-block px-2 py-1 text-xs rounded-full {{ $skladchina->status_badge_classes }}">{{ $skladchina->status_label }}</span>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="mt-6">{{ $skladchinas->withQueryString()->links() }}</div>
    </div>
</x-app-layout>
