<!doctype html>
<html lang="{{ $locale }}">

<head>
    @php
        $title = __('theme/default.title', ['partner_one' => $wedding->partner_one, 'partner_two' => $wedding->partner_two]);
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="You are invited to our special day!">
    <meta property="og:image" content="{{ $wedding->og_image_url }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Inter:wght@300;400;600&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @vite(['resources/css/filament/admin/theme.css', 'resources/css/app.css', 'resources/js/app.js'])

    <style>
        @font-face {
            font-family: "MyanmarHopong";
            src: local('MyanmarHopong'), url('https://cdn.jsdelivr.net/gh/zFont/Host@main/Font/Language/Myanmar/MyanmarHopong.ttf') format('truetype');
        }

        body {
            font-family: 'Inter','MyanmarHopong', sans-serif;
        }

        .font-script {
            font-family: 'Great Vibes','MyanmarHopong', cursive;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.75);
            /* Slightly more transparency */
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        /* Swiper Customization - Pale Pink Theme */
        .swiper-pagination-bullet-active {
            background-color: #f472b6 !important;
            /* pink-400 */
        }

        .swiper-pagination-bullet {
            background-color: #fbcfe8;
            /* pink-200 */
            opacity: 1;
        }

        /* Snow Canvas */
        #snow-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
        }
    </style>
</head>

<body class="bg-[#fff5f7] text-slate-600 antialiased overflow-x-hidden selection:bg-pink-200 selection:text-pink-900">

    <canvas id="snow-canvas"></canvas>

    <main class="w-full min-h-screen relative">

        <div class="relative w-full h-[85vh] flex items-center justify-center overflow-hidden">
            @php
                $heroImage = $wedding->og_image_url;
            @endphp
            <div class="absolute inset-0 z-0">
                <img src="{{ $heroImage }}"
                    class="w-full h-full object-cover object-center brightness-[0.85] blur-[2px] scale-105 animate-pulse-slow"
                    alt="Background">
                <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-pink-900/10 to-[#fff5f7]"></div>
            </div>

            <div class="relative z-10 text-center text-white px-4 animate-fade-in-up">

                <h1
                    class="myanmar-text font-script text-5xl md:text-6xl lg:text-7xl mb-6 drop-shadow-md leading-tight text-white/95">
                    {{ $wedding->partner_one }} <br class="md:hidden"> <span
                        class="mx-2 text-4xl md:text-6xl text-pink-200">&</span> <br class="md:hidden">
                    {{ $wedding->partner_two }}
                </h1>

                <p class="uppercase tracking-[0.4em] text-xs md:text-sm mb-4 opacity-90 font-light">
                    {{ __('theme/default.event_subtitle') }}</p>

                <div
                    class="mt-8 glass-panel text-pink-500 inline-block px-8 py-3 rounded-[3rem] shadow-lg shadow-pink-200/20">
                    <p class="text-xl md:text-2xl font-semibold tracking-wide">{{ $guest->name }}</p>
                    <p class="mt-1 text-[10px] uppercase tracking-widest text-gray-700 font-medium">
                        {{ __('theme/default.invitee_subtitle') }}</p>
                </div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto -mt-20 relative z-20 px-4 pb-20 space-y-10">

            <div class="glass-panel p-10 rounded-[2.5rem] shadow-xl shadow-pink-100/60 ring-1 ring-white/60">
                <h2 class="font-script text-4xl text-center text-pink-400 mb-6">{{ __('theme/default.content_title') }}
                </h2>
                <div class="prose prose-pink mx-auto text-center text-slate-600 leading-loose font-light">
                    {!! $wedding->content_renderer !!}
                </div>
            </div>

            <div
                class="p-8 rounded-[2.5rem] shadow-lg shadow-pink-100/50 bg-white/80 backdrop-blur-sm text-center border border-white">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 divide-y md:divide-y-0 md:divide-x divide-pink-100">

                    <div class="flex flex-col items-center p-2">
                        <div class="bg-pink-50 p-3 rounded-full mb-3">
                            <x-heroicon-o-calendar class="w-6 h-6 text-pink-400" />
                        </div>
                        <span class="text-sm font-medium text-slate-600 tracking-wide">
                            {{ $wedding->event_date->locale($locale)->translatedFormat(__('filament/admin/manage_wedding.event_date_format')) }}
                        </span>
                    </div>

                    <div class="flex flex-col items-center p-2 pt-8 md:pt-2">
                        <div class="bg-pink-50 p-3 rounded-full mb-3">
                            <x-heroicon-o-clock class="w-6 h-6 text-pink-400" />
                        </div>
                        <span
                            class="text-sm font-medium text-slate-600 tracking-wide">{{ $wedding->event_time ?? '00:00' }}</span>
                    </div>

                    <div class="flex flex-col items-center p-2 pt-8 md:pt-2">
                        <div class="bg-pink-50 p-3 rounded-full mb-3">
                            <x-heroicon-o-map-pin class="w-6 h-6 text-pink-400" />
                        </div>
                        <span class="text-sm font-medium text-slate-600 tracking-wide">{{ $wedding->address }}</span>
                    </div>
                </div>
            </div>

            @if($wedding->images->count() > 0)
                <div
                    class="bg-white p-5 rounded-[2.5rem] shadow-xl shadow-pink-100/50 overflow-hidden border border-pink-50/50">
                    <h2 class="font-script text-3xl text-center text-pink-400 mb-6 mt-2">
                        {{ __('theme/default.prewedding_images_title') }}</h2>
                    <div class="swiper mySwiper w-full h-[300px] md:h-[500px] rounded-[2rem]">
                        <div class="swiper-wrapper">
                            @foreach($wedding->images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ $image->url }}" alt="{{ $image->name }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            @endif

            @if($wedding->address_url)
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-pink-100/50 text-center border border-pink-50">
                    <h2 class="font-script text-3xl text-pink-400 mb-8">{{ __('theme/default.address') }}</h2>

                    <div class="flex flex-col md:flex-row gap-8 items-center justify-center">
                        <div class="flex flex-col items-center gap-4">
                            <div class="p-4 bg-pink-50 rounded-2xl">
                                <div id="qrcode" class="mix-blend-multiply opacity-90"></div>
                            </div>
                            <span
                                class="text-[10px] text-pink-300 uppercase tracking-widest font-bold">{{ __('theme/default.address_map_scan') }}</span>
                        </div>

                        <div class="flex flex-col gap-4 w-full md:w-auto">
                            <a href="{{ $wedding->address_url }}" target="_blank"
                                class="flex items-center justify-center gap-2 bg-pink-400 hover:bg-pink-500 text-white px-8 py-3.5 rounded-full shadow-lg shadow-pink-200 transition-all transform hover:-translate-y-0.5">
                                <x-heroicon-o-map-pin class="w-5 h-5" />
                                <span class="font-medium">{{ __('theme/default.open_google_maps') }}</span>
                            </a>

                            @php
                                $startDate = $wedding->event_date->format('Ymd');
                                $endDate = $wedding->event_date->copy()->addDay()->format('Ymd');
                                $calendarDetails = urlencode($wedding->address);
                                $calendarLocation = urlencode($wedding->address_url ?: $wedding->address);
                                $gCalUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text=$title&dates=$startDate/$endDate&details=$calendarDetails&location=" . $calendarLocation . "&sf=true&output=xml";
                            @endphp
                            <a href="{{ $gCalUrl }}" target="_blank"
                                class="flex items-center justify-center gap-2 bg-white hover:bg-pink-50 text-slate-500 hover:text-pink-500 px-8 py-3.5 rounded-full border border-pink-100 transition-colors">
                                <x-heroicon-o-calendar class="w-5 h-5" />
                                <span class="font-medium">{{ __('theme/default.add_to_calendar') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if($guest->is_notable)
                @if($guest->note)
                    <div
                        class="bg-green-50/80 backdrop-blur-sm text-green-600 p-6 rounded-[2rem] text-center border border-green-100 mb-4 shadow-sm">
                        {{ __('theme/default.note_sent') }}
                    </div>
                @else
                    <div class="glass-panel p-10 rounded-[2.5rem] shadow-xl shadow-pink-100/40 border border-pink-100">
                        <h2 class="font-script text-3xl text-center text-pink-400 mb-2">{{ __('theme/default.note_title') }}
                        </h2>
                        <p class="text-center mt-2 text-slate-400 mb-8 font-light text-sm">
                            {{ __('theme/default.note_subtitle') }}</p>

                        @if(session('success'))
                            <div class="bg-green-50 text-green-600 p-4 rounded-2xl text-center border border-green-100 mb-4">
                                {{ session('success') }}
                            </div>
                        @elseif(\Illuminate\Support\Str::of($guest->note)->trim()->isNotEmpty())
                            <div class="bg-pink-50/50 p-8 rounded-[2rem] text-center italic text-pink-800 border border-pink-100">
                                "{{ $guest->note }}"
                                <div class="mt-4 text-xs text-pink-300 font-bold uppercase not-italic tracking-wider">
                                    {{ __('theme/default.note_sent') }}</div>
                            </div>
                        @else
                            <form method="POST"
                                action="{{ route('guests.submitNote', ['locale' => $locale, 'weddingSlug' => $wedding->slug, 'guestSlug' => $guest->slug]) }}"
                                class="space-y-5">
                                @csrf
                                <div>
                                    <textarea name="note" rows="4"
                                        class="w-full border border-pink-100 outline-none rounded-2xl focus:border-pink-300 focus:ring-2 focus:ring-pink-100 bg-white/60 p-5 placeholder:text-pink-200 text-slate-600 transition-all"
                                        placeholder="{{ __('theme/default.note_placeholder') }}" required></textarea>
                                </div>
                                <button type="submit"
                                    class="w-full bg-pink-400 hover:bg-pink-500 text-white font-medium tracking-wide py-4 rounded-2xl shadow-lg shadow-pink-200 transition-transform active:scale-[0.99]">
                                    {{ __('theme/default.btn_send_note') }}
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            @endif

            <div class="text-center text-pink-300 text-sm pb-10">
                <p>&copy; {{ date('Y') }} {{ $wedding->partner_one }} & {{ $wedding->partner_two }}</p>
                <p class="text-xs mt-2 flex items-center justify-center gap-1">
                    Made with <span class="text-pink-400">♥</span>
                </p>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        // Initialize Swiper
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 30,
            centeredSlides: true,
            effect: "fade",
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });

        // Initialize QR Code - Updated Color to match Pink theme
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "{{ $wedding->address_url }}",
            width: 128,
            height: 128,
            colorDark: "#f472b6", // pink-400
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        // Snow Effect
        (function () {
            const canvas = document.getElementById('snow-canvas');
            const ctx = canvas.getContext('2d');
            let width, height;
            let particles = [];

            function resize() {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            }

            function initParticles() {
                const count = width < 768 ? 50 : 100;
                for (let i = 0; i < count; i++) {
                    particles.push({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        r: Math.random() * 3 + 1,
                        d: Math.random() * count,
                        s: Math.random() * 1 + 0.5
                    });
                }
            }

            function draw() {
                ctx.clearRect(0, 0, width, height);
                // Slightly softer white for the snow to match the pale theme
                ctx.fillStyle = "rgba(255, 255, 255, 0.9)";
                ctx.beginPath();
                for (let i = 0; i < particles.length; i++) {
                    let p = particles[i];
                    ctx.moveTo(p.x, p.y);
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2, true);
                }
                ctx.fill();
                update();
                requestAnimationFrame(draw);
            }

            let angle = 0;

            function update() {
                angle += 0.01;
                for (let i = 0; i < particles.length; i++) {
                    let p = particles[i];
                    p.y += Math.cos(angle + p.d) + 1 + p.r / 2;
                    p.x += Math.sin(angle) * 2;

                    if (p.x > width + 5 || p.x < -5 || p.y > height) {
                        if (i % 3 > 0) {
                            particles[i] = { x: Math.random() * width, y: -10, r: p.r, d: p.d, s: p.s };
                        } else {
                            if (Math.sin(angle) > 0) {
                                particles[i] = { x: -5, y: Math.random() * height, r: p.r, d: p.d, s: p.s };
                            } else {
                                particles[i] = { x: width + 5, y: Math.random() * height, r: p.r, d: p.d, s: p.s };
                            }
                        }
                    }
                }
            }

            window.addEventListener('resize', resize);
            resize();
            initParticles();
            draw();
        })();
    </script>
</body>

</html>