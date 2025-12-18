<?php
/*
 * Copyright (c) 2025 Kaung Khant Kyaw and Khun Htetz Naing.
 *
 * This file is part of the PaungPhet app.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */
?>

@use(App\Constants\SupportedLocale)

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        @font-face {
            font-family: "MyanmarHopong";
            src: local('MyanmarHopong'), url('https://cdn.jsdelivr.net/gh/zFont/Host@main/Font/Language/Myanmar/MyanmarHopong.ttf') format('truetype');
        }
    </style>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <!-- Vite Assets -->
    @vite(['resources/css/welcome.css'])

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="aurora-bg text-slate-700 antialiased selection:bg-pink-200 selection:text-pink-900 overflow-x-hidden">

<!-- Background Animation Canvas -->
<canvas id="petals-canvas"></canvas>

<!-- Navigation -->
<nav class="fixed w-full z-50 glass-nav transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14 items-center">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="size-8">
                </div>
                <div>
                    <span
                        class="font-serif font-bold text-2xl tracking-wide gradient-text block leading-none pt-2">{{ config('app.name') }}</span>
                </div>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-6">
                <a href="https://github.com/paungphet/paungphet" target="_blank"
                   class="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors bg-white/50 ps-3 pe-2 py-1.5 rounded-full border border-slate-200 hover:border-slate-400">
                    <i data-lucide="github" class="w-4 h-4"></i>
                    <span>Open Source</span>
                    <span class="bg-blue-100 text-blue-700 text-[10px] px-1.5 py-0.5 rounded-lg font-bold">AGPL v3.0</span>
                </a>

                <div class="h-6 w-px bg-slate-300"></div>

                <!-- Language Switcher (Integrated into Nav) -->
                @php
                    $supportedLocale = SupportedLocale::from($locale);
                    $currentFlag = $supportedLocale->flag();
                @endphp

                <div class="relative">
                    <button id="locale-btn"
                            class="flex items-center gap-2 px-3 py-2 rounded-full bg-slate-50 hover:bg-slate-100 transition-colors text-slate-600 font-medium text-sm cursor-pointer">
                        @svg("flag-4x3-$currentFlag", 'size-4 object-cover')
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </button>
                    <!-- Dropdown -->
                    <div id="locale-dropdown"
                         class="absolute right-0 mt-2 w-40 py-2 glass-card rounded-xl opacity-0 invisible translate-y-2 transition-all duration-200 z-60">
                        @foreach(SupportedLocale::all() as $loc)
                            <a href="{{ route('welcome', ['locale' => $loc['code']]) }}"
                               class="flex items-center gap-3 px-4 py-2 hover:bg-white/50 text-sm font-medium @if($locale === $loc['code']) text-pink-600 @else text-slate-600 @endif">
                                @svg("flag-4x3-{$loc['flag']}", 'size-4 object-cover')
                                <span class="text-xs font-medium">{{ $loc['name'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('filament.admin.auth.login') }}" class="text-sm font-semibold text-slate-600 hover:text-pink-600 transition-colors">{{ __('welcome.btn_log_in') }}</a>

                <a href="{{ route('filament.admin.auth.register') }}"
                   class="bg-linear-to-r from-primary to-secondary hover:from-pink-600 hover:to-blue-600 text-white p-0.5 rounded-full text-sm font-semibold shadow-xs hover:shadow-md transition-all duration-100">
                    <div class="bg-white text-black rounded-full px-3 py-1.5">
                        {{ __('welcome.btn_sign_up') }}
                    </div>
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-btn" class="p-2 rounded-md text-slate-600 hover:bg-slate-100">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Container (Hidden by default) -->
    <div id="mobile-menu" class="hidden md:hidden absolute top-14 left-0 w-full bg-white border-t border-gray-100 shadow-md transition-all duration-300 z-50">
        <div class="px-4 py-4 space-y-4">
            <!-- Mobile Locale Switcher -->
            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                <div class="flex gap-2">
                    @foreach(SupportedLocale::all() as $loc)
                        <a href="{{ route('welcome', ['locale' => $loc['code']]) }}"
                           class="p-2 rounded-lg {{ $locale === $loc['code'] ? 'bg-pink-100 ring-1 ring-pink-300' : 'bg-slate-50' }}">
                            @svg("flag-4x3-{$loc['flag']}", 'size-5 object-cover')
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="https://github.com/paungphet/paungphet" target="_blank" class="flex items-center gap-3 text-slate-600 hover:text-slate-900 py-2">
                <i data-lucide="github" class="w-5 h-5"></i>
                <span class="font-medium">Open Source (AGPL v3.0)</span>
            </a>

            <a href="{{ route('filament.admin.auth.login') }}" class="block text-slate-600 hover:text-pink-600 font-medium py-2">{{ __('welcome.btn_log_in') }}</a>


            <a href="{{ route('filament.admin.auth.register') }}" class="block w-full text-center bg-linear-to-r from-pink-500 to-blue-500 text-white rounded-lg font-bold p-0.5">
                <div class="bg-white text-black rounded-lg px-3 py-1.5">
                    {{ __('welcome.btn_sign_up') }}
                </div>
            </a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="relative pt-20 pb-12 lg:pt-20 lg:pb-12 overflow-hidden z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">

            <!-- Text Content -->
            <div class="lg:w-1/2 text-center lg:text-left animate-fade-in-up">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-100 border border-pink-200 text-pink-700 text-xs font-bold uppercase tracking-widest mb-6">
                        <span class="relative flex h-2 w-2">
                          <span
                              class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-pink-500"></span>
                        </span>
                    {{ __('welcome.version') }}
                </div>

                <h1 class="font-serif text-2xl lg:text-4xl font-bold mb-6 leading-normal">
                    <span>{!! nl2br(__('welcome.title', ['app_name' => '<span class="gradient-text">' . config('app.name') . '</span>'])) !!}</span>
                </h1>

                <p class="font-serif text-lg text-slate-600 mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    {{ __('welcome.description') }}
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                    <a href="{{ route('filament.admin.auth.register') }}"
                       class="w-full sm:w-auto px-6 py-3 bg-slate-900 text-white rounded-full font-semibold hover:bg-slate-800 transition-all flex items-center justify-center gap-2 shadow-xl hover:shadow-2xl hover:-translate-y-1">
                        {{ __('welcome.btn_create_invitation') }}
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    <a href="#support-us"
                       class="w-full sm:w-auto px-6 py-3 glass-card text-pink-600 rounded-full font-semibold hover:bg-white transition-all flex items-center justify-center gap-2 group hover:-translate-y-1">
                        <i data-lucide="heart" class="w-4 h-4 group-hover:fill-current"></i>
                        {{ __('welcome.btn_support_us') }}
                    </a>
                </div>

                <div class="mt-10 flex items-center justify-center lg:justify-start gap-6 text-slate-400">
                    <div class="flex items-center gap-2">
                        <i data-lucide="globe" class="w-5 h-5"></i>
                        <span class="text-sm">{{ __('welcome.n_locales_supported', ['count' => count($supportedLocales)]) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="layout-template" class="w-5 h-5"></i>
                        <span class="text-sm">{{ __('welcome.multiple_themes') }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. Slideshow Card -->
            <div class="lg:w-1/2 relative flex justify-center">

                <!-- Image Card -->
                <div
                    class="relative w-full max-w-sm aspect-4/5 glass-card p-3 rounded-3xl shadow-2xl transform rotate-2 hover:rotate-0 transition-all duration-500 animate-float">
                    <div class="w-full h-full rounded-2xl overflow-hidden relative bg-white shadow-inner">
                        <!-- Swiper -->
                        <div class="swiper mySwiper w-full h-full">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide relative">
                                    <img
                                        src="{{ asset('images/demo/prewedding_1.jpg') }}"
                                        class="w-full h-full object-cover" alt="Demo 1">
                                </div>
                                <div class="swiper-slide relative">
                                    <img
                                        src="{{ asset('images/demo/prewedding_2.jpg') }}"
                                        class="w-full h-full object-cover" alt="Demo 2">
                                </div>
                                <div class="swiper-slide relative">
                                    <img
                                        src="{{ asset('images/demo/prewedding_3.jpg') }}"
                                        class="w-full h-full object-cover" alt="Demo 3">
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>

                    <!-- Floating Badge -->
                    <div class="z-10 absolute -bottom-6 -right-6 glass-card px-6 py-4 rounded-2xl shadow-xl animate-bounce">
                        <div class="flex items-center gap-3">
                            <div class="bg-pink-100 p-2 rounded-full text-pink-500">
                                <i data-lucide="camera" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="font-serif text-slate-800 font-bold">{{ __('filament/admin/prewedding_image_resource.navigation_label') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Admin & Features Section -->
<section class="py-20 bg-white/40 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-serif text-3xl md:text-4xl font-bold mb-4">Powerful Dashboard</h2>
            <p class="text-slate-500 max-w-2xl mx-auto">Manage your special day with ease. Our intuitive
                FilamentPHP-powered admin panel puts you in control.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- 3. Admin Screenshot -->
            <div class="relative group">
                <div class="relative bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Placeholder for Admin Screenshot -->
                    <img src="{{ asset("images/dashboard/$locale/admin_panel.png") }}"
                         alt="Admin Panel"
                         class="w-full h-auto object-cover transform">
                </div>
            </div>

            <!-- Features List -->
            <div class="space-y-8">
                <div class="flex gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-pink-100 flex items-center justify-center text-pink-600 shrink-0">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-slate-800 mb-2">Smart Guest Management</h3>
                        <p class="text-slate-600 leading-relaxed">Add guests, generate unique links, and track who has
                            viewed your invitation. Keep your guest list organized and accessible.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                        <i data-lucide="languages" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-slate-800 mb-2">Native Language Support</h3>
                        <p class="text-slate-600 leading-relaxed">Speak to your family's heart. Fully supports English,
                            Myanmar, PaOh, and Shan locales out of the box.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 shrink-0">
                        <i data-lucide="map" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-slate-800 mb-2">Location & Details</h3>
                        <p class="text-slate-600 leading-relaxed">Integrate Google Maps, share precise locations, and
                            let guests save the date directly to their calendars.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Themes Section -->
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="font-serif text-3xl md:text-4xl font-bold">Stunning Themes</h2>
                <p class="text-slate-500 mt-2">Choose the perfect look for your big day.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Theme Card 1: Aurora -->
            <a class="group cursor-pointer" target="_blank" href="{{ config('app.url') . "/$locale/mg-and-may/invite/uncle-hla?theme=aurora" }}">
                <div class="relative overflow-hidden rounded-2xl shadow-lg border border-slate-100 aspect-video">
                    <img src="https://placehold.co/800x450/ffe4e6/be185d?text=Aurora+Theme+Preview"
                         alt="Aurora Theme"
                         class="w-full h-full object-cover transform transition duration-500 group-hover:scale-110">

                    <div
                        class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300 flex items-center justify-center">
                        <div
                            class="bg-white/90 backdrop-blur text-slate-800 px-6 py-2 rounded-lg opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 shadow-xs">
                            <i data-lucide="external-link" class="size-8"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Aurora</h3>
                        <p class="text-sm text-slate-500">Default elegant gradient theme</p>
                    </div>
                </div>
            </a>

            <!-- Theme Card 2: Default -->
            <a class="group cursor-pointer" target="_blank" href="{{ config('app.url') . "/$locale/mg-and-may/invite/uncle-hla" }}">
                <div class="relative overflow-hidden rounded-2xl shadow-lg border border-slate-100 aspect-video">
                    <img src="https://placehold.co/800x450/f1f5f9/334155?text=Default+Theme+Preview"
                         alt="Default Theme"
                         class="w-full h-full object-cover transform transition duration-500 group-hover:scale-110">

                    <div
                        class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300 flex items-center justify-center">
                        <div
                            class="bg-white/90 backdrop-blur text-slate-800 px-6 py-2 rounded-lg opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 shadow-xs">
                            <i data-lucide="external-link" class="size-8"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Default</h3>
                        <p class="text-sm text-slate-500">Clean, classic, snow theme</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Support Us / Donation Section (New) -->
<section id="support-us" class="py-12 bg-white/60 backdrop-blur-md">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="p-10 rounded-3xl shadow-xs border border-pink-100">
            <div class="w-16 h-16 mx-auto mb-6 bg-pink-100 rounded-full flex items-center justify-center text-pink-500 animate-pulse">
                <i data-lucide="heart-handshake" class="w-8 h-8"></i>
            </div>
            <p class="font-serif leading-relaxed text-lg text-slate-600 mb-8 max-w-2xl mx-auto">
                {{ __('welcome.msg_support_us', ['app_name' => config('app.name')]) }}
            </p>

            <div class="flex items-center justify-center gap-4">
                <!-- Facebook Contact Button -->
                <a href="https://www.facebook.com/paungphet" target="_blank"
                   class="py-2 px-3 flex items-center justify-center bg-pink-50 text-pink-600 border border-pink-200 rounded-md hover:bg-pink-100 hover:scale-110 transition-all shadow-xs"
                   title="Contact us on Facebook">
                    <i data-lucide="facebook" class="size-6"></i>
                    <span class="ms-2">Facebook</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Footer / Open Source -->
<footer class="bg-white border-t border-slate-200 pt-16 pb-8 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="size-8">
                    </div>
                    <span class="font-serif font-bold text-xl gradient-text">{{ config('app.name') }}</span>
                </div>
                <p class="text-slate-500 mb-6 max-w-sm">
                    An open-source wedding invitation project crafted with love.
                    Licensed under <a href="#" class="text-blue-600 underline">GNU AGPL v3.0</a> for personal use and
                    open development.
                </p>
                <div class="flex gap-4">
                    <a href="https://github.com/kaungkhantjc/paungphet"
                       class="text-slate-400 hover:text-slate-900 transition-colors">
                        <i data-lucide="github" class="w-6 h-6"></i>
                    </a>
                    <a href="#" class="text-slate-400 hover:text-blue-500 transition-colors">
                        <i data-lucide="facebook" class="w-6 h-6"></i>
                    </a>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-slate-800 mb-4">Project</h4>
                <ul class="space-y-2 text-sm text-slate-500">
                    <li><a href="https://github.com/paungphet/paungphet" class="hover:text-pink-600 transition-colors">GitHub Repository</a></li>
                    <li><a href="https://github.com/PaungPhet/PaungPhet/issues" class="hover:text-pink-600 transition-colors">Report an Issue</a></li>
                    <li><a href="#support-us" class="hover:text-pink-600 transition-colors">{{ __('welcome.btn_support_us') }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-slate-800 mb-4">Legal</h4>
                <ul class="space-y-2 text-sm text-slate-500">
                    <li><a href="https://github.com/PaungPhet/PaungPhet/blob/main/LICENSE" class="hover:text-pink-600 transition-colors">AGPL License</a></li>
                    <li><a href="#" class="hover:text-pink-600 transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-pink-600 transition-colors">Terms of Service</a></li>
                </ul>
            </div>
        </div>

        <div
            class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-400 text-center md:text-left">
            <p>&copy; 2025 Kaung Khant Kyaw & Khun Htetz Naing. All rights reserved.</p>
            <p>Made with ❤️ in Myanmar.</p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    // Initialize Swiper
    var swiper = new Swiper(".mySwiper", {
        effect: "fade",
        fadeEffect: {crossFade: true},
        grabCursor: true,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        loop: true
    });

    // Language Switcher Logic
    (function () {
        const btn = document.getElementById('locale-btn');
        const menu = document.getElementById('locale-dropdown');
        let isOpen = false;

        function toggleMenu(show) {
            isOpen = show;
            if (show) {
                menu.classList.remove('opacity-0', 'invisible', 'translate-y-2');
                menu.classList.add('opacity-100', 'visible', 'translate-y-0');
            } else {
                menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
                menu.classList.remove('opacity-100', 'visible', 'translate-y-0');
            }
        }

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMenu(!isOpen);
        });

        document.addEventListener('click', (e) => {
            if (isOpen && !btn.contains(e.target) && !menu.contains(e.target)) {
                toggleMenu(false);
            }
        });
    })();

    // Mobile Menu Toggle Logic
    (function () {
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    })();

    // Petals Animation
    (function () {
        const canvas = document.getElementById('petals-canvas');
        const ctx = canvas.getContext('2d');
        let width, height;
        const petals = [];

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }

        class Petal {
            constructor() {
                this.reset();
            }

            reset() {
                this.x = Math.random() * width;
                this.y = -20;
                this.z = Math.random() * 0.5 + 0.5;
                this.vx = (Math.random() - 0.5) * 0.5;
                this.vy = Math.random() * 0.5 + 0.5;
                this.rotation = Math.random() * 360;
                this.rotationSpeed = (Math.random() - 0.5) * 2;
                this.size = (Math.random() * 8 + 4) * this.z;
                this.opacity = Math.random() * 0.3 + 0.2;
                this.color = Math.random() > 0.5 ? 'rgba(236, 72, 153,' : 'rgba(59, 130, 246,';
            }

            update() {
                this.x += this.vx;
                this.y += this.vy * this.z;
                this.rotation += this.rotationSpeed;

                if (this.y > height + 20 || this.x < -20 || this.x > width + 20) {
                    this.reset();
                }
            }

            draw() {
                ctx.save();
                ctx.translate(this.x, this.y);
                ctx.rotate(this.rotation * Math.PI / 180);
                ctx.globalAlpha = this.opacity;

                ctx.beginPath();
                ctx.ellipse(0, 0, this.size, this.size * 1.5, 0, 0, Math.PI * 2);
                ctx.fillStyle = this.color + this.opacity + ')';
                ctx.fill();

                ctx.restore();
            }
        }

        function init() {
            const count = width < 768 ? 15 : 30;
            for (let i = 0; i < count; i++) {
                petals.push(new Petal());
            }
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);
            petals.forEach(petal => {
                petal.update();
                petal.draw();
            });
            requestAnimationFrame(animate);
        }

        window.addEventListener('resize', () => {
            resize();
            petals.length = 0;
            init();
        });

        resize();
        init();
        animate();
    })();
</script>
</body>
</html>
