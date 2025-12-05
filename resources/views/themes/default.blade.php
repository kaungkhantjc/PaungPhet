<!doctype html>
<html lang="{{ $locale }}">
<head>
    @php
        $title = __('theme/default.title', ['partner_one' => $wedding->partner_one, 'partner_two' => $wedding->partner_two]);
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <meta property="og:title"
          content="{{ $title }}">
    <meta property="og:description" content="You are invited to our special day!">
    <meta property="og:image" content="{{ $wedding->og_image_url }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Inter:wght@300;400;600&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-script {
            font-family: 'Great Vibes', cursive;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Swiper Customization */
        .swiper-pagination-bullet-active {
            background-color: #be185d !important;
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
<body class="bg-rose-50 text-slate-800 antialiased overflow-x-hidden selection:bg-rose-200">

<canvas id="snow-canvas"></canvas>

<main class="w-full min-h-screen relative">

    <div class="relative w-full h-[85vh] flex items-center justify-center overflow-hidden">
        @php
            $heroImage = $wedding->og_image_url;
        @endphp
        <div class="absolute inset-0 z-0">
            <img src="{{ $heroImage }}"
                 class="w-full h-full object-cover object-center brightness-75 blur-[2px] scale-105 animate-pulse-slow"
                 alt="Background">
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-rose-50"></div>
        </div>

        <div class="relative z-10 text-center text-white px-4 animate-fade-in-up">


            <h1 class="font-script text-4xl md:text-5xl lg:text-6xl mb-6 drop-shadow-lg leading-tight">
                {{ $wedding->partner_one }} <br class="md:hidden"> <span
                    class="mx-2 text-4xl md:text-6xl text-rose-200">&</span> <br
                    class="md:hidden"> {{ $wedding->partner_two }}
            </h1>

            <p class="uppercase tracking-[0.3em] text-sm md:text-base mb-4 opacity-90">{{ __('theme/default.event_subtitle') }}</p>

            <div class="mt-6 glass-panel text-slate-900 inline-block px-6 py-2 rounded-t-3xl shadow-2xl">
                <p class="text-xl md:text-2xl font-semibold">{{ $guest->name }}</p>
                <p class="mt-2 text-xs uppercase tracking-widest text-slate-500 mb-1">{{ __('theme/default.invitee_subtitle') }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto -mt-20 relative z-20 px-4 pb-20 space-y-8">

        <div class="glass-panel border border-white/50 p-8 rounded-3xl shadow-xl">
            <h2 class="font-script text-2xl text-center text-rose-600 mb-6">{{ __('theme/default.content_title') }}</h2>
            <div class="prose prose-rose prose-lg mx-auto text-center text-slate-600 leading-loose">
                {!! $wedding->content_renderer !!}
            </div>
        </div>

        <div class=" p-6 rounded-3xl shadow-sm bg-white text-center">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 divide-y md:divide-y-0 md:divide-x divide-rose-200">

                <div class="flex flex-col items-center p-2">
                    <x-heroicon-o-calendar class="w-8 h-8 text-rose-600 mb-2"/>
                    <span
                        class="text-sm font-medium">{{ $wedding->event_date->translatedFormat(__('filament/admin/manage_wedding.event_date_format')) }}</span>
                </div>

                <div class="flex flex-col items-center p-2 pt-6 md:pt-2">
                    <x-heroicon-o-clock class="w-8 h-8 text-rose-600 mb-2"/>
                    <span class="text-sm font-medium">{{ $wedding->event_time ?? '00:00' }}</span>
                </div>

                <div class="flex flex-col items-center p-2 pt-6 md:pt-2">
                    <x-heroicon-o-map-pin class="w-8 h-8 text-rose-600 mb-2"/>
                    <span class="text-sm font-medium">{{ $wedding->address }}</span>
                </div>
            </div>
        </div>


        @if($wedding->images->count() > 0)
            <div class="bg-white p-4 rounded-3xl shadow-sm overflow-hidden">
                <h2 class="font-script text-2xl text-center text-rose-600 mb-6">{{ __('theme/default.prewedding_images_title') }}</h2>
                <div class="swiper mySwiper w-full h-[300px] md:h-[500px] rounded-xl">
                    <div class="swiper-wrapper">
                        @foreach($wedding->images as $image)
                            <div class="swiper-slide">
                                <img src="{{ $image->url }}" alt="{{ $image->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        @endif

        @if($wedding->address_url)
            <div class="bg-white p-8 rounded-3xl shadow-sm text-center">
                <h2 class="font-script text-2xl text-rose-600 mb-8">{{ __('theme/default.address') }}</h2>

                <div class="flex flex-col md:flex-row gap-8 items-center justify-center">
                    <div class="flex flex-col items-center gap-3">
                        <div id="qrcode" class="p-3 border-2 border-rose-100 rounded-lg"></div>
                        <span
                            class="text-xs text-slate-400 uppercase tracking-widest">{{ __('theme/default.address_map_scan') }}</span>
                    </div>

                    <div class="flex flex-col gap-4 w-full md:w-auto">
                        <a href="{{ $wedding->address_url }}" target="_blank"
                           class="flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white px-6 py-3 rounded-2xl shadow-xs">
                            <x-heroicon-o-map-pin class="w-8 h-8"/>
                            {{ __('theme/default.open_google_maps') }}
                        </a>

                        @php
                            // Calculate End Time (Assuming 2 hours later if not set, for calendar event)
                            $startDate = $wedding->event_date->format('Ymd');
                            $endDate = $wedding->event_date->copy()->addDay()->format('Ymd');
                            $calendarDetails = urlencode($wedding->address);
                            $calendarLocation = urlencode($wedding->address_url ?: $wedding->address);
                            $gCalUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text=$title&dates=$startDate/$endDate&details=$calendarDetails&location=" . $calendarLocation . "&sf=true&output=xml";
                        @endphp
                        <a href="{{ $gCalUrl }}" target="_blank"
                           class="flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-3 rounded-2xl border border-slate-200 transition">
                            <x-heroicon-o-calendar class="w-8 h-8"/>
                            {{ __('theme/default.add_to_calendar') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @if($guest->is_notable)
            @if($guest->note)
                <div
                    class="bg-green-50 text-green-700 p-4 rounded-xl text-center border border-green-200 mb-4 animate-bounce">
                    {{ __('theme/default.note_sent') }}
                </div>
            @else
                <div class="glass-panel p-8 rounded-3xl shadow-xl border border-rose-100">
                    <h2 class="font-script text-2xl text-center text-rose-600 mb-2">{{ __('theme/default.note_title') }}</h2>
                    <p class="text-center mt-2 text-slate-500 mb-6">{{ __('theme/default.note_subtitle') }}</p>

                    @if(session('success'))
                        <div
                            class="bg-green-50 text-green-700 p-4 rounded-xl text-center border border-green-200 mb-4 animate-bounce">
                            {{ session('success') }}
                        </div>
                    @elseif(\Illuminate\Support\Str::of($guest->note)->trim()->isNotEmpty())
                        <div class="bg-rose-50 p-6 rounded-xl text-center italic text-rose-800 border border-rose-100">
                            "{{ $guest->note }}"
                            <div
                                class="mt-2 text-xs text-rose-400 font-bold uppercase not-italic">{{ __('theme/default.note_sent') }}</div>
                        </div>
                    @else
                        <form method="POST"
                              action="{{ route('guests.submitNote', ['locale' => $locale, 'weddingSlug' => $wedding->slug, 'guestSlug' => $guest->slug]) }}"
                              class="space-y-4">
                            @csrf
                            <div>
                            <textarea
                                name="note"
                                rows="4"
                                class="w-full border-2 outline-none rounded-xl border-rose-200 focus:border-rose-500 focus:ring-rose-500 bg-white/80 p-4"
                                placeholder="{{ __('theme/default.note_placeholder') }}"
                                required></textarea>
                            </div>
                            <button type="submit"
                                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 rounded-xl shadow-xs">
                                {{ __('theme/default.btn_send_note') }}
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        @endif

        <div class="text-center text-slate-400 text-sm pb-10">
            <p>&copy; {{ date('Y') }} {{ $wedding->partner_one }} & {{ $wedding->partner_two }}</p>
            <p class="text-xs mt-1">Made with love</p>
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
        effect: "fade", // Nice fade effect for weddings
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });

    // Initialize QR Code
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: "{{ $wedding->address_url }}",
        width: 128,
        height: 128,
        colorDark: "#e11d48", // Rose-600
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    // Snow Effect (Custom Lightweight Implementation)
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
            const count = width < 768 ? 50 : 100; // Less snow on mobile
            for (let i = 0; i < count; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    r: Math.random() * 3 + 1, // Radius
                    d: Math.random() * count, // Density
                    s: Math.random() * 1 + 0.5 // Speed
                });
            }
        }

        function draw() {
            ctx.clearRect(0, 0, width, height);
            ctx.fillStyle = "rgba(255, 255, 255, 0.8)";
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
                        particles[i] = {x: Math.random() * width, y: -10, r: p.r, d: p.d, s: p.s};
                    } else {
                        if (Math.sin(angle) > 0) {
                            particles[i] = {x: -5, y: Math.random() * height, r: p.r, d: p.d, s: p.s};
                        } else {
                            particles[i] = {x: width + 5, y: Math.random() * height, r: p.r, d: p.d, s: p.s};
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
