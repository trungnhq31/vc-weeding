@php
    use Filament\Support\Enums\Width;
    use Filament\Auth\Pages\Login;

    $livewire ??= null;
    $renderHookScopes = $livewire?->getRenderHookScopes();
    $maxContentWidth ??= (filament()->getSimplePageMaxContentWidth() ?? Width::Large);

    if (is_string($maxContentWidth)) {
        $maxContentWidth = Width::tryFrom($maxContentWidth) ?? $maxContentWidth;
    }

    // Detect if current page is the Login page for 2-column layout
    $isLoginPage = $livewire instanceof Login;
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    @props([
        'after' => null,
        'heading' => null,
        'subheading' => null,
    ])

    @if ($isLoginPage)
        {{-- 2-Column Wedding Login Layout (inline styles to bypass Tailwind purge in vendor views) --}}
        <style>
            /* ── Wrapper ─────────────────────────────────────── */
            .wl-wrap {
                display: flex;
                min-height: 100vh;
                background: #f1f5f9;  /* light slate — shows behind the floating card */
            }

            /* ── Left photo column ───────────────────────────── */
            .wl-photo {
                display: none;
                position: relative;
                overflow: hidden;
                background: #0f172a;
                flex-shrink: 0;
            }
            .wl-photo img {
                position: absolute; inset: 0;
                width: 100%; height: 100%;
                object-fit: cover; object-position: center;
            }
            /* Layer 1: subtle dark tone across entire image for overall contrast */
            .wl-photo-overlay-tone {
                position: absolute; inset: 0;
                background: rgba(10, 10, 20, 0.28);
            }
            /* Layer 2: strong gradient rising from bottom for caption readability */
            .wl-photo-overlay-grad {
                position: absolute; inset: 0;
                background: linear-gradient(
                    to top,
                    rgba(8, 8, 18, 0.92) 0%,
                    rgba(8, 8, 18, 0.55) 30%,
                    rgba(8, 8, 18, 0.10) 60%,
                    transparent 100%
                );
            }
            .wl-photo-caption {
                position: absolute; bottom: 0; left: 0; right: 0;
                padding: 2.5rem;
                color: #fff;
                z-index: 10;
            }
            .wl-photo-divider {
                width: 2.5rem; height: 2px;
                background: rgba(244,114,182,0.7);
                border-radius: 2px;
                margin-bottom: 1rem;
            }
            .wl-photo-tag {
                display: flex; align-items: center; gap: 0.5rem;
                font-size: 0.68rem; font-weight: 700;
                letter-spacing: 0.14em; text-transform: uppercase;
                margin-bottom: 0.65rem;
                color: rgba(253,210,218,0.9);
                text-shadow: 0 1px 4px rgba(0,0,0,0.4);
            }
            .wl-photo-name {
                font-family: Georgia, 'Times New Roman', serif;
                font-size: 3.5rem; font-weight: 700;
                color: #fff;
                line-height: 1.05; letter-spacing: -0.02em;
                margin-bottom: 0.6rem;
                text-shadow: 0 2px 12px rgba(0,0,0,0.45);
            }
            .wl-photo-venue {
                font-size: 0.825rem; font-weight: 500;
                color: rgba(255,255,255,0.75);
                text-shadow: 0 1px 6px rgba(0,0,0,0.5);
            }
            .wl-photo-note {
                font-size: 0.68rem; margin-top: 0.75rem;
                color: rgba(255,255,255,0.38);
            }

            /* ── Right form column ───────────────────────────── */
            .wl-form-col {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 3rem 2rem;
                background: #f1f5f9;  /* matches wrapper so card floats */
            }
            /* The floating card */
            .wl-form-inner {
                width: 100%;
                max-width: 24rem;
                background: #ffffff;
                border-radius: 1.25rem;
                padding: 2.5rem 2rem;
                box-shadow:
                    0 4px 6px -1px rgba(0,0,0,0.07),
                    0 10px 30px -5px rgba(0,0,0,0.10),
                    0 0 0 1px rgba(226,232,240,0.8);
            }
            .wl-brand { text-align: center; margin-bottom: 2rem; }
            .wl-logo {
                display: inline-flex; align-items: center; justify-content: center;
                width: 3rem; height: 3rem;
                border-radius: 0.875rem;
                background: #fff5f6; border: 1px solid #fecdd3;
                margin-bottom: 1rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.07);
            }
            .wl-logo svg { width: 1.5rem; height: 1.5rem; color: #e11d48; }
            .wl-title {
                font-family: Georgia, 'Times New Roman', serif;
                font-size: 1.5rem; font-weight: 700;
                color: #0f172a; margin: 0 0 0.25rem;
            }
            .wl-subtitle { font-size: 0.875rem; color: #64748b; margin: 0; }

            @media (min-width: 1024px) {
                .wl-photo { display: flex; width: 55%; }
                .wl-form-col { width: 45%; flex: none; }
            }
        </style>

        <div class="wl-wrap">
            {{-- Left: Pre-wedding Photo --}}
            <div class="wl-photo">
                <img src="{{ asset('images/prewedding-login-bg.png') }}" alt="Vân & Cẩm Pre-wedding" />
                {{-- Layer 1: overall tone --}}
                <div class="wl-photo-overlay-tone"></div>
                {{-- Layer 2: bottom-up gradient for caption --}}
                <div class="wl-photo-overlay-grad"></div>
                <div class="wl-photo-caption">
                    <div class="wl-photo-divider"></div>
                    <div class="wl-photo-tag">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        Save The Date &nbsp;•&nbsp; 19 . 12 . 2026
                    </div>
                    <div class="wl-photo-name">Vân &amp; Cẩm</div>
                    <p class="wl-photo-venue">Thứ Bảy, 19 Tháng 12 Năm 2026 &nbsp;•&nbsp; Asiana Plaza, TP. Hồ Chí Minh</p>
                    <p class="wl-photo-note">✦ Hình ảnh tham khảo — sẽ cập nhật ảnh thật sau khi chụp pre-wedding</p>
                </div>
            </div>

            {{-- Right: Login Form --}}
            <div class="wl-form-col">
                <div class="wl-form-inner">
                    <div class="wl-brand">
                        <div class="wl-logo">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </div>
                        <h1 class="wl-title">Personal Hub &amp; Wedding</h1>
                        <p class="wl-subtitle">Đăng nhập để quản lý kế hoạch cưới</p>
                    </div>

                    {{-- Filament Login Form Slot --}}
                    {{ $slot }}
                </div>
            </div>
        </div>
    @else
        {{-- Default simple layout for all other Filament pages --}}
        <div class="fi-simple-layout">
            @if (($hasTopbar ?? true) && filament()->auth()->check())
                <a href="#fi-main-content" class="fi-skip-link fi-sr-only">
                    {{ __('filament-panels::layout.skip_to_content.label') }}
                </a>
            @endif

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

            @if (($hasTopbar ?? true) && filament()->auth()->check())
                <div class="fi-simple-layout-header">
                    @if (filament()->hasDatabaseNotifications())
                        @livewire(filament()->getDatabaseNotificationsLivewireComponent(), [
                            'lazy' => filament()->hasLazyLoadedDatabaseNotifications(),
                            'position' => \Filament\Enums\DatabaseNotificationsPosition::Topbar,
                        ])
                    @endif

                    @if (filament()->hasUserMenu())
                        @livewire(Filament\Livewire\SimpleUserMenu::class)
                    @endif
                </div>
            @endif

            <div class="fi-simple-main-ctn">
                <main
                    id="fi-main-content"
                    tabindex="-1"
                    @class([
                        'fi-simple-main',
                        ($maxContentWidth instanceof Width) ? "fi-width-{$maxContentWidth->value}" : $maxContentWidth,
                    ])
                >
                    {{ $slot }}
                </main>
            </div>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $renderHookScopes) }}

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
        </div>
    @endif
</x-filament-panels::layout.base>
