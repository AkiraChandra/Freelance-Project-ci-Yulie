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
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .ocean-gradient {
                background: linear-gradient(135deg, #0c1d3f 0%, #0f3460 30%, #16518a 60%, #1a8a8a 100%);
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.97);
                backdrop-filter: blur(20px);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);
            }
            .wave-pattern {
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                overflow: hidden;
                line-height: 0;
            }
            .wave-pattern svg {
                position: relative;
                display: block;
                width: calc(100% + 1.3px);
                height: 150px;
            }
            .float-animation {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-15px); }
            }
            .ship-icon {
                animation: sail 8s ease-in-out infinite;
            }
            @keyframes sail {
                0%, 100% { transform: translateX(0) rotate(0deg); }
                25% { transform: translateX(8px) rotate(1deg); }
                75% { transform: translateX(-8px) rotate(-1deg); }
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex ocean-gradient relative overflow-hidden">

            <!-- Decorative circles -->
            <div class="absolute top-20 left-10 w-72 h-72 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-32 right-20 w-96 h-96 bg-teal-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-cyan-400/5 rounded-full blur-2xl"></div>

            <!-- Left Side - Branding -->
            <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-12 relative z-10">
                <div class="text-white max-w-lg">
                    <!-- Ship icon -->
                    <div class="mb-8 ship-icon">
                        <svg class="w-20 h-20 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M3.75 21l1.5-4.5h13.5l1.5 4.5M3.75 21H2.25m17.25 0h1.5M6 16.5l-1.125-3.375A1.125 1.125 0 015.953 12h12.094a1.125 1.125 0 011.078 1.125L18 16.5M6 16.5h12M12 3v9m0-9l3 3m-3-3L9 6" />
                        </svg>
                    </div>

                    <p class="text-teal-300 font-semibold text-sm tracking-[0.2em] uppercase mb-3">Expedisi Muatan Kapal Laut</p>
                    <h1 class="text-4xl xl:text-5xl font-extrabold mb-3 leading-tight">
                        PT. Suryasumatera<br>
                        <span class="text-teal-300">Indahsejahtera</span>
                    </h1>
                    <p class="text-lg text-slate-300 mb-10 leading-relaxed">Sistem manajemen internal untuk operasional import, export, dan pengelolaan invoice.</p>

                    <div class="space-y-5">
                        <div class="flex items-center space-x-4">
                            <div class="w-11 h-11 bg-teal-500/20 rounded-xl flex items-center justify-center flex-shrink-0 border border-teal-400/30">
                                <svg class="w-5 h-5 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-white">Kelola Order & Invoice</p>
                                <p class="text-sm text-slate-400">Import, export, dan cetak nota</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-11 h-11 bg-teal-500/20 rounded-xl flex items-center justify-center flex-shrink-0 border border-teal-400/30">
                                <svg class="w-5 h-5 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87M15 11a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-white">Staff & Vendor</p>
                                <p class="text-sm text-slate-400">Manajemen SDM dan mitra kerja</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-11 h-11 bg-teal-500/20 rounded-xl flex items-center justify-center flex-shrink-0 border border-teal-400/30">
                                <svg class="w-5 h-5 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-white">Approval Workflow</p>
                                <p class="text-sm text-slate-400">Persetujuan penugasan real-time</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-8 relative z-10">
                <div class="w-full max-w-md">
                    <!-- Mobile logo -->
                    <div class="lg:hidden text-center mb-8">
                        <p class="text-teal-300 font-semibold text-xs tracking-[0.15em] uppercase">EMKL</p>
                        <h2 class="text-2xl font-extrabold text-white">PT. Suryasumatera</h2>
                        <p class="text-teal-300 font-bold">Indahsejahtera</p>
                    </div>

                    <div class="glass-card rounded-3xl p-8 lg:p-10">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Wave -->
            <div class="wave-pattern">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none" fill="rgba(255,255,255,0.03)">
                    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C57.1,118.92,163.64,74.18,321.39,56.44Z"></path>
                </svg>
            </div>
        </div>
    </body>
</html>
