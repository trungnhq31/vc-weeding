<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ config('app.name', 'Personal Hub & Wedding') }}</title>
        @unless(app()->environment('testing'))
            @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        @endunless
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100">
        @inertia
    </body>
</html>
