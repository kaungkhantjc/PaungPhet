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

    <!doctype html>
<html lang="{{ $locale }}" class="scroll-smooth">

<head>
    @php
        $title = __('theme/default.title', ['partner_one' => $wedding->partner_one, 'partner_two' => $wedding->partner_two]);
        $description = $guest
            ? __('theme/default.invitee_description', ['name' => $guest->name])
            : __('theme/default.all_invitees_subtitle');
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <meta property="og:title" content="{{ $description }}">
    <meta property="og:description" content="{{ $title }}">
    <meta property="og:image" content="{{ $wedding->og_image_url }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    {{-- Fonts: Noto Serif Myanmar & Inter (Sans) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Serif+Myanmar:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    {{-- Tailwind & App Assets --}}
    @vite(['resources/css/filament/admin/theme.css', 'resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-primary: #ec4899;
            --color-secondary: #3b82f6;
            --gradient-aurora: linear-gradient(135deg, #fce7f3 0%, #dbeafe 50%, #e0e7ff 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #fefcff 0%, #fef3f8 50%, #f0f9ff 100%);
        }

        .font-main {
            font-family: 'Noto Serif Myanmar', serif;
            font-weight: 600;
            font-style: normal;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #fce7f3;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #ec4899, #3b82f6);
            border-radius: 10px;
        }

        .swiper-pagination-bullet-active {
            background: linear-gradient(135deg, #ec4899, #3b82f6) !important;
        }

        .swiper-pagination-bullet {
            background-color: #e5e7eb;
            opacity: 1;
        }

        #petals-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 50;
        }

        .gradient-text {
            background: linear-gradient(135deg, #ec4899, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(229, 229, 229, 0.8);
        }

        .aurora-glow {
            box-shadow: 0 0 30px rgba(236, 72, 153, 0.2), 0 0 60px rgba(59, 130, 246, 0.1);
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .fade-in-scale {
            animation: fadeInScale 0.8s ease-out forwards;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .float-anim {
            animation: float 3s ease-in-out infinite;
        }

        /* Toast Animation */
        @keyframes slideDownFade {
            0% {
                transform: translateY(-100%);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }

        .animate-toast-enter {
            animation: slideDownFade 0.5s ease-out forwards;
        }

        .animate-toast-exit {
            animation: fadeOut 0.5s ease-in forwards;
        }
    </style>
</head>

<body class="text-gray-700 antialiased overflow-x-hidden selection:bg-pink-200 selection:text-pink-900">

<canvas id="petals-canvas"></canvas>

{{-- Toast Notification --}}
@if(session('success'))
    <div id="toast-wrapper" class="fixed top-18 left-0 w-full flex justify-center z-[150] pointer-events-none">
        <div id="toast-notification"
             class="pointer-events-auto bg-white/80 backdrop-blur-xl border border-pink-100 shadow-2xl shadow-pink-200/50 rounded-full px-6 py-3 flex items-center gap-3 animate-toast-enter">
            <div class="bg-gradient-to-br from-pink-100 to-blue-100 rounded-full p-1">
                <x-heroicon-m-check class="w-4 h-4 text-pink-500"/>
            </div>
            <span class="gradient-text font-semibold text-sm">{{ session('success') }}</span>
            <button onclick="closeToast()"
                    class="ml-2 text-gray-400 hover:text-pink-500 transition-colors cursor-pointer">
                <x-heroicon-m-x-mark class="w-4 h-4"/>
            </button>
        </div>
    </div>
@endif

{{-- Locale Switcher --}}
<div class="fixed top-6 right-6 z-[100]">
    @php
        $currentRouteName = $guest ? 'guests.invite' : 'guests.show';
        $baseParams = ['weddingSlug' => $wedding->slug, 'theme' => $theme];
        if ($guest) $baseParams['guestSlug'] = $guest->slug;
        $supportedLocale = SupportedLocale::from($locale);
        $currentFlag = $supportedLocale->flag();
    @endphp

    <div class="relative">
        {{-- Button: Added ID --}}
        <button id="locale-btn"
                class="glass-card shadow-sm px-4 py-2.5 rounded-full flex items-center gap-2 transition-all hover:shadow-xl hover:scale-105 cursor-pointer">
            <div class="w-5 h-5 rounded-full overflow-hidden shadow-sm">
                @svg("flag-4x3-$currentFlag", 'w-full h-full object-cover')
            </div>
            <span class="text-xs font-semibold text-gray-700 uppercase">{{ $supportedLocale->label() }}</span>
            <x-heroicon-m-chevron-down class="w-3 h-3 text-gray-500"/>
        </button>

        {{-- Dropdown: Added ID and Removed group-hover classes --}}
        <div id="locale-dropdown"
             class="absolute right-0 mt-2 w-40 py-2 glass-card shadow-2xl rounded-2xl opacity-0 invisible translate-y-2 transition-all duration-200">
            @foreach(SupportedLocale::all() as $loc)
                @php
                    $isActive = $locale === $loc['code'];
                    $url = route($currentRouteName, array_merge($baseParams, ['locale' => $loc['code']]));
                @endphp
                <a href="{{ $url }}"
                   class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/50 rounded-lg mx-1 {{ $isActive ? 'text-pink-600' : 'text-gray-600' }}">
                    <div class="w-4 h-4 rounded-full overflow-hidden border border-gray-200">
                        @svg("flag-4x3-{$loc['flag']}", 'w-full h-full object-cover')
                    </div>
                    <span class="text-xs font-medium">{{ $loc['name'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

<main class="w-full min-h-screen relative font-main">

    {{-- 1. Hero Section --}}
    <div class="relative min-h-screen w-full flex items-center justify-center overflow-hidden">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ $wedding->bg_image_url ?? $wedding->og_image_url }}"
                 class="w-full h-full object-cover"
                 alt="Wedding Background">
            <div class="absolute inset-0 bg-gradient-to-b from-pink-50/60 via-white/70 to-blue-50/60"></div>
        </div>

        {{-- Content --}}
        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto fade-in-scale pb-12">
            <div class="mb-6 flex justify-center">
                <div class="h-0.5 w-20 bg-gradient-to-r from-transparent via-pink-500 to-transparent"></div>
            </div>

            <h1 class="font-semibold text-4xl md:text-5xl mb-4">
                <span class="pt-10 block gradient-text pb-0!">{{ $wedding->partner_one }}</span>
                <span class="text-5xl md:text-6xl text-gray-400 font-light">&</span>
                <span class="pt-10 block gradient-text">{{ $wedding->partner_two }}</span>
            </h1>

            <p class="text-pink-500 font-semibold tracking-[0.1em] md:tracking-[0.2em] text-md md:text-lg uppercase mt-4 mb-4">
                {{ __('theme/default.event_subtitle') }}
            </p>

            @if($guest)
                <div class="mt-8 inline-block float-anim">
                    <div class="glass-card px-10 py-2 shadow-2xl rounded-3xl aurora-glow">
                        <p class="pt-3 text-xl md:text-2xl gradient-text font-semibold">{{ $guest->name }}</p>
                        <p class="text-[10px] uppercase tracking-[0.1em] md:tracking-[0.2em] text-gray-700 mt-2 mb-2">
                            {{ __('theme/default.invitee_subtitle') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2">
            <div class="animate-bounce">
                <div class="w-6 h-10 border-2 border-gray-600 rounded-full p-1">
                    <div class="w-1 h-2 bg-gradient-to-b from-pink-500 to-blue-500 rounded-full mx-auto"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Wrapper --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 space-y-14">

        {{-- 2. Story Section --}}
        <div class="glass-card border border-gray-200 p-8 md:p-10 rounded-3xl shadow-sm text-center fade-in-scale">
            <div class="mb-8">
                <div class="inline-block px-6 pb-1 pt-4 rounded-full bg-gradient-to-r from-pink-100 to-blue-100 mb-4">
                    <span
                        class="text-lg pt-3 font-semibold gradient-text uppercase tracking-wider">{{ __('theme/default.content_title') }}</span>
                </div>

            </div>
            <div class="prose prose-lg prose-gray mx-auto text-gray-600 leading-relaxed">
                {!! $wedding->content_renderer !!}
            </div>
        </div>

        {{-- 3. Event Details --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 fade-in-scale">
            {{-- Date --}}
            <div
                class="glass-card p-8 rounded-3xl shadow-sm text-center group hover:shadow-md hover:-translate-y-2 transition-all duration-300">
                <div
                    class="w-16 h-16 mx-auto mb-8 rounded-full bg-gradient-to-br from-pink-100 to-pink-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <x-heroicon-o-calendar class="w-8 h-8 text-pink-600"/>
                </div>
                <p class="text-gray-600 font-medium">
                    {{ $wedding->event_date->locale($locale)->translatedFormat(__('filament/admin/manage_wedding.event_date_format')) }}
                </p>
            </div>

            {{-- Time --}}
            <div
                class="glass-card p-8 rounded-3xl shadow-sm text-center group hover:shadow-md hover:-translate-y-2 transition-all duration-300">
                <div
                    class="w-16 h-16 mx-auto mb-8 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <x-heroicon-o-clock class="w-8 h-8 text-purple-600"/>
                </div>
                <p class="text-gray-600 font-medium">{{ $wedding->event_time ?? '00:00' }}</p>
            </div>

            {{-- Place --}}
            <div
                class="glass-card p-8 rounded-3xl shadow-sm text-center group hover:shadow-md hover:-translate-y-2 transition-all duration-300">
                <div
                    class="w-16 h-16 mx-auto mb-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <x-heroicon-o-map-pin class="w-8 h-8 text-blue-600"/>
                </div>
                <p class="text-gray-600 font-medium">{{ $wedding->address }}</p>
            </div>
        </div>

        {{-- 4. Gallery --}}
        @if($wedding->images->count() > 0)
            <div class="glass-card p-6 md:p-8 rounded-3xl shadow-xl fade-in-scale">
                <div class="text-center mb-10">
                    <div
                        class="inline-block px-6 pb-1 pt-4 rounded-full bg-gradient-to-r from-pink-100 to-blue-100 mb-4">
                        <span
                            class="text-lg pt-3 font-semibold gradient-text uppercase tracking-wider">{{ __('theme/default.prewedding_images_title') }}</span>
                    </div>
                </div>

                <div class="swiper gallerySwiper w-full h-[400px] md:h-[600px] rounded-2xl overflow-hidden shadow-sm">
                    <div class="swiper-wrapper">
                        @foreach($wedding->images as $image)
                            <div class="swiper-slide">
                                <img src="{{ $image->url }}" alt="{{ $image->name }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-700 rounded-xl">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                    <div
                        class="swiper-button-prev !text-white !bg-white/30 !backdrop-blur-sm !size-18 !rounded-full"></div>
                    <div
                        class="swiper-button-next !text-white !bg-white/30 !backdrop-blur-sm !size-18 !rounded-full"></div>
                </div>
            </div>
        @endif

        {{-- 5. Location --}}
        @if($wedding->address_url)
            <div class="glass-card rounded-3xl shadow-2xl overflow-hidden fade-in-scale">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-0">
                    {{-- QR Section --}}
                    <div
                        class="md:col-span-2 bg-gradient-to-br from-pink-50 to-blue-50 p-10 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-200">
                        <div class="bg-white p-4 rounded-2xl shadow-sm mb-6">
                            <div id="qrcode"></div>
                        </div>
                        <p class="text-xs text-gray-500 uppercase tracking-[0.1em] text-center">
                            {{ __('theme/default.address_map_scan') }}
                        </p>
                    </div>

                    {{-- Details Section --}}
                    <div class="md:col-span-3 p-10">
                        <div
                            class="inline-block px-6 pb-1 pt-4 rounded-full bg-gradient-to-r from-pink-100 to-blue-100 mb-4">
                            <span
                                class="text-lg pt-3 font-semibold gradient-text uppercase tracking-wider">{{ __('theme/default.address') }}</span>
                        </div>
                        <p class="text-gray-600 mb-8 leading-relaxed">{{ $wedding->address }}</p>

                        <div class="space-y-3">
                            <a href="{{ $wedding->address_url }}" target="_blank"
                               class="flex items-center justify-center gap-3 w-full bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white px-6 py-4 rounded-full transition-all shadow-lg hover:shadow-xl font-semibold">
                                <x-heroicon-m-map class="w-5 h-5"/>
                                <span class="pt-3">{{ __('theme/default.open_google_maps') }}</span>
                            </a>

                            @php
                                $startDate = $wedding->event_date->format('Ymd');
                                $endDate = $wedding->event_date->copy()->addDay()->format('Ymd');
                                $calendarDetails = urlencode($wedding->address);
                                $calendarLocation = urlencode($wedding->address_url ?: $wedding->address);
                                $gCalUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text=$title&dates=$startDate/$endDate&details=$calendarDetails&location=" . $calendarLocation . "&sf=true&output=xml";
                            @endphp
                            <a href="{{ $gCalUrl }}" target="_blank"
                               class="flex items-center justify-center gap-3 w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-4 rounded-full transition-all shadow-lg hover:shadow-xl font-semibold">
                                <x-heroicon-m-calendar class="w-5 h-5"/>
                                <span class="pt-3">{{ __('theme/default.add_to_calendar') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 6. Note Submission --}}
        @if($guest && $guest->is_notable)
            <div class="fade-in-scale">
                <div class="glass-card p-10 md:p-16 rounded-3xl shadow-xl relative overflow-hidden">
                    {{-- Decorative Elements --}}
                    <div
                        class="absolute -top-20 -right-20 w-60 h-60 bg-gradient-to-br from-pink-200/30 to-blue-200/30 rounded-full blur-3xl"></div>
                    <div
                        class="absolute -bottom-20 -left-20 w-60 h-60 bg-gradient-to-tr from-purple-200/30 to-pink-200/30 rounded-full blur-3xl"></div>

                    <div class="relative z-10">
                        <div class="text-center mb-12">
                            <div
                                class="inline-block px-6 pb-1 pt-4 rounded-full bg-gradient-to-r from-pink-100 to-blue-100 mb-4">
                                <span
                                    class="text-lg pt-3 font-semibold gradient-text uppercase tracking-wider">{{ __('theme/default.note_title') }}</span>
                            </div>
                            <p class="mt-2 text-gray-500 max-w-2xl mx-auto">{{ __('theme/default.note_subtitle') }}</p>
                        </div>

                        {{-- Refactored: Toast handles success. Here we just show the content if it exists. --}}
                        @if(\Illuminate\Support\Str::of($guest->note)->trim()->isNotEmpty())
                            <div class="glass-card p-12 text-center rounded-2xl border-2 border-pink-100">
                                <div
                                    class="w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-pink-100 to-blue-100 rounded-full flex items-center justify-center">
                                    <x-heroicon-s-heart class="w-8 h-8 text-pink-500"/>
                                </div>
                                <p class="text-gray-700 text-xl md:text-2xl italic leading-relaxed mb-6">
                                    "{{ $guest->note }}"</p>
                                <div
                                    class="inline-block px-4 py-1.5 rounded-full bg-gradient-to-r from-pink-100 to-blue-100">
                                    <span
                                        class="text-xs font-semibold gradient-text uppercase tracking-wider">{{ __('theme/default.note_sent') }}</span>
                                </div>
                            </div>
                        @else
                            <form method="POST"
                                  action="{{ route('guests.submitNote', ['locale' => $locale, 'weddingSlug' => $wedding->slug, 'guestSlug' => $guest->slug]) }}"
                                  class="max-w-2xl mx-auto">
                                @csrf

                                <input type="hidden" name="theme" value="{{ $theme }}">

                                <div class="mb-6">
                                    <textarea name="note" id="note" rows="6"
                                              class="w-full outline-none glass-card border-2 border-gray-200 focus:border-pink-400 focus:ring-4 focus:ring-pink-100 rounded-2xl px-6 py-4 transition-all resize-none placeholder:text-gray-400"
                                              placeholder="{{ __('theme/default.note_placeholder') }}"
                                              required></textarea>
                                </div>
                                <button type="submit"
                                        class="w-full bg-gradient-to-r from-pink-500 via-purple-500 to-blue-500 hover:from-pink-600 hover:via-purple-600 hover:to-blue-600 text-white font-bold pt-4 pb-2 rounded-full shadow-2xl transition-all duration-300 hover:scale-[1.02] uppercase tracking-wider">
                                    <span class="pt-3">{{ __('theme/default.btn_send_note') }}</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Footer --}}
        <div class="text-center pt-16 pb-8">
            <div class="mb-4 h-0.5 w-32 bg-gradient-to-r from-transparent via-gray-300 to-transparent mx-auto"></div>
            <p class="text-3xl gradient-text mb-3 pt-4">{{ $wedding->partner_one }} & {{ $wedding->partner_two }}</p>
            <p class="text-xs text-gray-400 uppercase tracking-[0.3em]">&copy; {{ date('Y') }}</p>
        </div>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    // Swiper Gallery
    new Swiper(".gallerySwiper", {
        loop: true,
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        coverflowEffect: {
            rotate: 20,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: true,
        },
        speed: 1000,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });

    // QR Code
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $wedding->address_url }}",
        width: 120,
        height: 120,
        colorDark: "#ec4899",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    // Toast Notification Logic
    function closeToast() {
        const toast = document.getElementById('toast-notification');
        const wrapper = document.getElementById('toast-wrapper');
        if(toast) {
            toast.classList.remove('animate-toast-enter');
            toast.classList.add('animate-toast-exit');
            setTimeout(() => {
                toast.remove();
                if(wrapper) wrapper.remove();
            }, 500);
        }
    }

    // Auto-dismiss toast
    document.addEventListener('DOMContentLoaded', () => {
        const toast = document.getElementById('toast-notification');
        if(toast) {
            setTimeout(() => {
                closeToast();
            }, 4000); // Disappear after 4 seconds
        }
    });

    // Floating Petals Effect
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
            const count = width < 768 ? 20 : 40;
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

    // Locale Switcher Logic (Vanilla JS)
    (function() {
        const btn = document.getElementById('locale-btn');
        const menu = document.getElementById('locale-dropdown');
        let isOpen = false;

        function toggleMenu(show) {
            isOpen = show;
            if (show) {
                // Remove hidden state classes
                menu.classList.remove('opacity-0', 'invisible', 'translate-y-2');
                // Add visible state classes
                menu.classList.add('opacity-100', 'visible', 'translate-y-0');
            } else {
                // Revert to hidden state
                menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
                menu.classList.remove('opacity-100', 'visible', 'translate-y-0');
            }
        }

        // Toggle on button click
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMenu(!isOpen);
        });

        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (isOpen && !btn.contains(e.target) && !menu.contains(e.target)) {
                toggleMenu(false);
            }
        });
    })();

</script>
</body>
</html>
