<!DOCTYPE html>
<html
    lang="en"
    x-data
    class="{{ $theme ?? 'dark' }}"
>
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    {{ $metatags ?? "" }}
    <!-- Favicon Icon -->
    <link rel="icon" href="{{ asset("favicon.png") }}" />
    <!-- Site Title -->
    <title>@yield("title", config("app.name"))</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
    @stack('styles')
    @livewireStyles
</head>
<body>

{{ $navbar ?? "" }}

<main>
    {{ $slot }}
</main>

{{ $footer ?? "" }}

@livewireScripts
@stack("scripts")
</body>
</html>
