<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- SEO (start) --}}
    <title>{{ config('app.name', 'Sosro Career') }}</title>
    <meta name="description" content="Temukan lowongan kerja terbaru di PT Sinar Sosro Gunung Slamat.">
    <meta name="keywords"
        content="sosro, teh botol sosro, sosro career, karir sosro, lowongan kerja sosro, rekrutmen pt sinar sosro gunung slamat, 
                loker pabrik teh, gunung slamat career, sosro lowongan, sosro rekrutmen, karir pabrik teh, loker teh sosro, sosro job, sosro karir, 
                gunung slamat lowongan, pabrik teh sosro rekrutmen, sosro hiring, sosro recruitment, gunung slamat career, fmcg karir, fmcg sosro, 
                lowongan fmcg indonesia, sosro fmcg job, karir fmcg teh, loker fmcg pabrik, sosro fmcg recruitment">
    <meta property="og:title" content="Karir PT Sinar Sosro Gunung Slamat">
    <meta property="og:description"
        content="Bergabunglah bersama PT Sinar Sosro Gunung Slamat. Cek lowongan aktif di karir.sosro.com">
    <meta property="og:url" content="https://karir.sosro.com">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('assets/images/logo sosro.webp') }}">
    {{-- SEO (end) --}}
    <link rel="icon" href="{{ asset('assets/images/logo sosro.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Noto+Sans+JP:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <style>
        *,
        body {
            font-family: "Noto Sans JP", sans-serif;
            /* font-family: serif; */
        }

        /* SOSRO Brand Loader */
        .loader {
            display: flex;
            align-items: center;
        }

        .bar {
            display: inline-block;
            width: 4px;
            height: 22px;
            background-color: #B11116;
            /* MERAH BRAND */
            border-radius: 10px;
            animation: sosro-loading 1s linear infinite;
        }

        .bar:nth-child(2) {
            height: 32px;
            margin: 0 6px;
            animation-delay: .25s;
        }

        .bar:nth-child(3) {
            animation-delay: .5s;
        }

        @keyframes sosro-loading {
            20% {
                background-color: #7a0c22;
                /* darker red highlight */
                transform: scaleY(1.5);
            }

            40% {
                transform: scaleY(1);
            }
        }

        .job-card ul {
            list-style-type: disc !important;
            margin-left: 1.25rem !important;
        }

        .job-card ol {
            list-style-type: decimal !important;
            margin-left: 1.25rem !important;
        }

        .job-card strong {
            font-weight: 700 !important;
        }

        .job-card em {
            font-style: italic !important;
        }

        .job-card u {
            text-decoration: underline !important;
        }

        select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
        }

        /* Menghilangkan panah di IE/Edge */
        select::-ms-expand {
            display: none !important;
        }
    </style>

    <x-turnstile.scripts />
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="max-w-[1800px] mx-auto relative">

        {{-- Loader --}}
        {{-- @include('guest.templates.components.loader') --}}

        {{-- Alert Scam --}}
        {{-- @include('guest.templates.components.alert_scam') --}}

        {{-- Navbar --}}
        @include('guest.templates.navbar')

        {{-- Script for toggle menu mobile --}}
        @include('guest.templates.components.toggle_menu_mobile')

        {{-- Content --}}
        <main>
            @yield('content')
        </main>

        @include('guest.templates.footer')

    </div>

    @include('guest.templates.all_script_page')
</body>

</html>
