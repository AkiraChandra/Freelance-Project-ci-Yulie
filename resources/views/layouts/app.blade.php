<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PT. Suryasumatera Indahsejahtera') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
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
