@php
    use Filament\Facades\Filament;
    use Filament\Support\Facades\FilamentView;

    $hasTopbar = method_exists(Filament::getCurrentPanel(), 'hasTopNavigation') && Filament::getCurrentPanel()->hasTopNavigation();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="filament h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng Nhập - Personal Hub & Wedding</title>
    @filamentStyles
    @vite('resources/css/app.css')
</head>
<body class="filament-body h-full antialiased">

<div class="min-h-screen flex">
    <!-- Left Column: Pre-wedding Photo Background -->
    <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 relative overflow-hidden">
        <!-- Background Image -->
        <img 
            src="{{ asset('images/prewedding-login-bg.png') }}" 
            alt="Vân & Cẩm Pre-wedding"
            class="absolute inset-0 w-full h-full object-cover object-center"
        />
        <!-- Soft Overlay Gradient for Text Readability -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent to-slate-900/10"></div>

        <!-- Bottom Caption Overlay -->
        <div class="absolute bottom-0 left-0 right-0 p-10 text-white">
            <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-rose-200/80 mb-3">
                <svg class="w-4 h-4 fill-rose-300/80" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                Save The Date
            </div>
            <h2 class="text-4xl font-serif font-bold text-white leading-tight mb-2">
                Vân & Cẩm
            </h2>
            <p class="text-white/70 text-sm font-medium">
                Thứ Bảy, 19 Tháng 12 Năm 2026 &nbsp;•&nbsp; Asiana Plaza, TP. Hồ Chí Minh
            </p>
        </div>
    </div>

    <!-- Right Column: Minimalist Login Form -->
    <div class="w-full lg:w-1/2 xl:w-2/5 flex items-center justify-center bg-white px-8 py-12">
        <div class="w-full max-w-sm">
            <!-- Logo & Heading -->
            <div class="mb-10 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 mb-4 shadow-xs">
                    <svg class="w-6 h-6 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </div>
                <h1 class="text-2xl font-serif font-bold text-slate-900">Personal Hub & Wedding</h1>
                <p class="text-slate-500 text-sm mt-1">Đăng nhập để quản lý kế hoạch cưới</p>
            </div>

            <!-- Filament Login Form Component -->
            @livewire('filament.pages.auth.login')
        </div>
    </div>
</div>

@filamentScripts
@vite('resources/js/app.ts')
</body>
</html>
