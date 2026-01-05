@php
    $theme = $page->theme;
@endphp

<x-cms::layout.guest>
    <x-slot:theme>
        {{ $theme->value }}
    </x-slot:theme>

    <x-slot:metatags>
        <title>{{ ($page->seo_metadata['meta_title'] ?? $page->title) . ' - ' . config('app.name', 'Laravel') }}</title>
        <link rel="canonical" href="{{ $page->url() }}"/>

        <meta name="description" content="{{ $page->seo_metadata['meta_description'] ?? '' }}">

        @if($page->no_index)
            <meta name="robots" content="noindex">
        @endif

        <meta property="og:title" content="{{ $page->seo_metadata['og_title'] ?? $page->seo_metadata['meta_title'] }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ $page->url() }}">
        <meta property="og:description" content="{{ $page->seo_metadata['og_description'] ?? ($page->seo_metadata['meta_description'] ?? '') }}">
        <meta property="og:image" content="{{ $page->og_image_resolved?->getUrl() ?? asset('images/meta-logo.png') }}"/>
        <meta property="og:image:alt" content="{{ $page->title }}"/>
    </x-slot:metatags>

{{--    <x-slot:navbar>--}}
{{--        <x-navbar :bg="$theme->getPageBackground()"/>--}}
{{--    </x-slot:navbar>--}}

    <div class="{{ $theme->getPageBackground() }}">
        @foreach($page->content as $block)
            <div class="pb-32">
                <x-dynamic-component
                    :component="'cms::blocks.' . $block['type']"
                    :data="$block['data']"
                />
            </div>
        @endforeach
    </div>

{{--    <x-slot:footer>--}}
{{--        <x-layout.shared.footer :bg="$theme->getPageBackground()"/>--}}
{{--    </x-slot:footer>--}}
</x-cms::layout.guest>
