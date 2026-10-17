<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PT. Suryasumatera Indahsejahtera') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <script id="tailwind-config">
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            "primary": "#00685f",
                            "primary-container": "#008378",
                            "secondary": "#006c49",
                            "tertiary": "#924628",
                            "surface": "#f8f9ff",
                            "surface-variant": "#d5e3fc",
                            "background": "#f8f9ff",
                            "on-background": "#0d1c2e",
                            "on-surface": "#0d1c2e",
                            "on-surface-variant": "#3d4947",
                            "error": "#ba1a1a",
                            "on-error": "#ffffff",
                        },
                        fontFamily: {
                            sans: ['Inter', 'Plus Jakarta Sans', 'sans-serif'],
                            display: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                display: inline-block;
                vertical-align: middle;
                line-height: 1;
            }
            [x-cloak] { display: none !important; }
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .app-shell { min-height: 100vh; }
            .app-shell:has(.app-sidebar.is-open) { overflow: hidden; }
            .app-sidebar { width: 300px; transition: width .22s ease, transform .22s ease; }
            .app-sidebar.is-collapsed { width: 76px; }
            .app-sidebar.is-collapsed .sidebar-label,
            .app-sidebar.is-collapsed .sidebar-brand-copy,
            .app-sidebar.is-collapsed .sidebar-section-label,
            .app-sidebar.is-collapsed .sidebar-user-copy,
            .app-sidebar.is-collapsed .sidebar-chevron { display: none; }
            .app-sidebar.is-collapsed .sidebar-link { justify-content: center; padding-left: .75rem; padding-right: .75rem; }
            .app-sidebar.is-collapsed .sidebar-brand { justify-content: center; padding-left: .75rem; padding-right: .75rem; }
            .app-sidebar.is-collapsed .sidebar-user { justify-content: center; }
            .app-main { min-width: 0; }
            .dashboard-staff-surface { background: #f4f7fb; }
            .order-progress-track {
                position: absolute;
                top: 50%;
                left: 10%;
                right: 10%;
                height: 3px;
                transform: translateY(-50%);
                border-radius: 9999px;
                background: #e2e8f0;
                overflow: hidden;
                pointer-events: none;
            }
            .order-progress-fill {
                height: 100%;
                width: 0;
                border-radius: inherit;
                background: linear-gradient(90deg, #14b8a6, #0f9488, #34d399);
                transition: width .55s cubic-bezier(.22, 1, .36, 1);
            }
            .order-progress-fill::after {
                content: '';
                display: block;
                width: 40%;
                height: 100%;
                border-radius: inherit;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,.85), transparent);
                animation: order-progress-shimmer 1.7s ease-in-out infinite;
            }
            .order-progress-step {
                position: relative;
                z-index: 1;
                transition: background-color .3s ease, border-color .3s ease, box-shadow .3s ease, color .3s ease, transform .3s ease;
            }
            .order-progress-step.is-current {
                background: #0d9488;
                border-color: #0d9488;
                color: white;
                box-shadow: 0 0 0 4px rgba(20, 184, 166, .14), 0 8px 18px rgba(13, 148, 136, .22);
                transform: translateY(-2px);
            }
            .order-progress-step.is-current .order-step-number { background: white; color: #0f766e; }
            .order-progress-step.is-current .order-step-title { color: white; }
            .order-progress-step.is-current .order-step-copy { color: #ccfbf1; }
            .order-progress-step.is-done { background: #f0fdfa; border-color: #99f6e4; }
            .order-progress-step.is-done .order-step-number { background: #0d9488; color: white; }
            .order-progress-step.is-done .order-step-copy { color: #0f766e; }
            @keyframes order-progress-shimmer {
                from { transform: translateX(-150%); }
                to { transform: translateX(350%); }
            }
            .order-stepper-sticky {
                position: sticky;
                top: 0.75rem;
                z-index: 40;
            }
            .field-invalid {
                border-color: rgb(244 63 94 / 1) !important;
                box-shadow: 0 0 0 3px rgb(244 63 94 / .18) !important;
            }
            @media (max-width: 767px) {
                .app-sidebar { position: fixed; inset: 0 auto 0 0; width: min(300px, 86vw); transform: translateX(-105%); z-index: 70; }
                .app-sidebar.is-open { transform: translateX(0); }
                .app-sidebar.is-collapsed { width: min(300px, 86vw); }
                .app-sidebar.is-collapsed .sidebar-label,
                .app-sidebar.is-collapsed .sidebar-brand-copy,
                .app-sidebar.is-collapsed .sidebar-section-label,
                .app-sidebar.is-collapsed .sidebar-user-copy,
                .app-sidebar.is-collapsed .sidebar-chevron { display: initial; }
                .app-sidebar.is-collapsed .sidebar-link { justify-content: flex-start; padding-left: .75rem; padding-right: .75rem; }
                .app-sidebar.is-collapsed .sidebar-brand { justify-content: flex-start; padding-left: 1.5rem; padding-right: 1.5rem; }
                .app-sidebar.is-collapsed .sidebar-user { justify-content: flex-start; }
            }
            @media (prefers-reduced-motion: reduce) {
                .app-sidebar, .app-sidebar * { transition-duration: .01ms !important; }
            }
        </style>
    </head>
    <body x-data="{ sidebarCollapsed: false, mobileOpen: false, sidebarAccountOpen: false, profileOpen: false }" @keydown.escape.window="mobileOpen = false; sidebarAccountOpen = false; profileOpen = false" :class="{ 'overflow-hidden': mobileOpen }" class="app-shell flex font-sans antialiased">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="flex-1 py-8 dashboard-staff-surface">
            {{ $slot }}
        </main>
    </div>
    </body>
</html>
