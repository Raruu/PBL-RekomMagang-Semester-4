<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Remagang</title>
    <link rel="icon" href="{{ asset('imgs/logo_low_padding.webp') }}" type="image/webp">
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>

<body class="bg-[#1a1c29] text-white">

    <!-- Navbar -->
    <header class="flex justify-between items-center px-6 py-3 border-b border-gray-700 bg-[#1a1c29] sticky top-0 z-10">
        <a href="#hero" class="text-gray-400 text-sm hover:text-red-500">Beranda Utama</a>
        <div class="font-script text-white text-xl flex flex-row relative ml-6">
            <img src="{{ asset('imgs/logo.webp') }}" alt=""
                class="h-auto w-32 absolute left-[-46px] top-[-12px]">
            <p>emagang</p>
        </div>
        <a href="{{ route('login') }}">
            <button
                class="bg-[#fff] text-[#1a1c29] px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition  hover:bg-[#6566fc] hover:border-[#6566fc] hover:text-white">
                Masuk
            </button>
        </a>
    </header>

    <!-- Hero Section -->
    <section class="relative h-screen" id="hero">
        <img src="{{ asset('imgs/landing/landing.webp') }}" alt="Modern glass buildings"
            class="w-full h-screen object-cover brightness-[0.4]">
        <div class="absolute inset-0 flex flex-col justify-center items-start px-6 md:px-12 max-w-3xl">
            <h1 class="text-3xl md:text-5xl font-bold leading-tight mb-3">Selamat Datang di Remagang</h1>
            <p class="text-base md:text-lg font-semibold">Rekomendasi Magang Mahasiswa Yang Tepat dan Terpercaya
            </p>
            <p class="text-sm md:text-base mt-2 max-w-md leading-relaxed">Remagang hadir sebagai solusi cerdas untuk
                menemukan tempat magang sesuai minat, jurusan, dan keahlian mahasiswa.</p>
            <div class="mt-5 flex gap-4">
                <a href="{{ route('login') }}">
                    <button
                        class="px-5 py-3 bg-[#fff] transition rounded-lg text-[#1a1c29] hover:bg-[#6566fc] hover:border-[#6566fc] hover:text-white">
                        Masuk
                    </button>
                </a>
                <a href="{{ route('register') }}">
                    <button
                        class="border border-white px-5 py-3 rounded-lg hover:bg-white hover:text-[#1a1c29] transition">
                        Daftar
                    </button>
                </a>
            </div>
        </div>
    </section>

    <!-- Tentang Remagang -->
    <section class="max-w-7xl mx-auto px-6 py-48 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-3xl font-bold mb-6">Apa itu Sistem ReMagang?</h2>
            <p class="text-base font-light leading-relaxed">Platform digital yang membantu mahasiswa mendapatkan
                rekomendasi magang secara personal dan akurat. Didukung oleh data dan kemitraan dengan berbagai
                perusahaan, kami bantu kamu melangkah lebih dekat ke dunia kerja.</p>
        </div>
        {{-- <div class="flex justify-center">
            <div
                class="w-56 h-56 rounded-full bg-gradient-to-tr from-blue-400 to-purple-1000 flex justify-center items-center">
                <img src="{{ asset('imgs/logo.webp') }}" alt="Logo" class="w-56 h-56 object-contain"
                    id="picture-preview">
            </div>
        </div> --}}

    </section>

    <!-- Cara Penggunaan -->
    <section class="bg-[#222430] py-16">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <img src="{{ asset('imgs/landing/how-to-use.webp') }}" alt="usage illustration"
                    class="w-64 h-auto mx-auto">
            </div>
            <div>
                <h3 class="text-xl font-semibold mb-4">Cara Penggunaan Remagang</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm font-light">
                    <li>Daftar dan lengkapi profilmu</li>
                    <li>Lihat rekomendasi magang yang cocok</li>
                    <li>Ajukan lamaran langsung melalui platform</li>
                    <li>Dapatkan notifikasi proses rekrutmen</li>
                    <li>Mulai pengalaman magangmu!</li>
                </ol>
            </div>

        </div>
    </section>

    <!-- Perusahaan Mitra -->
    <section class="max-w-7xl mx-auto px-6 py-16">
        <h3 class="text-2xl font-bold mb-6">Perusahaan Mitra</h3>

        <div class="relative">
            <button class="carousel-control-prev absolute left-[-96px]" type="button"
                data-coreui-target="#carouselMitra" data-coreui-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <div id="carouselMitra" class="carousel slide" data-coreui-ride="carousel">
                <div class="carousel-indicators bottom-[-64px]">
                    @foreach ($perusahaanChunk as $index => $perusahaan)
                        <button type="button" data-coreui-target="#carouselMitra"
                            data-coreui-slide-to="{{ $index }}" class="{{ $loop->first ? 'active' : '' }}"
                            aria-current="{{ $loop->first ? 'true' : 'false' }}"
                            aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>

                <div class="carousel-inner">
                    @forelse($perusahaanChunk as $perusahaan)
                        <div class="carousel-item">
                            <div class="flex flex-row justify-around">
                                @foreach ($perusahaan as $mitra)
                                    <div
                                        class="min-w-[280px] max-w-xs flex-shrink-0 bg-[#222430] rounded-xl p-4 border border-gray-700 text-sm text-white">
                                        <h4 class="text-lg font-bold mb-2">{{ $mitra->nama_perusahaan }}</h4>
                                        <p class="text-gray-400 mb-1 hover:text-blue-600"><i
                                                class="fas fa-map-marker-alt mr-1"></i>
                                            <a href="https://maps.google.com/?q={{ $mitra->lokasi->latitude ?? '' }},{{ $mitra->lokasi->longitude ?? '' }}"
                                                target="_blank" rel="noopener noreferrer"
                                                onclick="event.stopPropagation();">
                                                {{ $mitra->lokasi->alamat ?? '-' }}
                                            </a>
                                        </p>
                                        <p class="mb-1"><i class="fas fa-industry mr-1 text-gray-500"></i>
                                            {{ $mitra->bidangIndustri->nama ?? 'Belum ada bidang' }}
                                        </p>
                                        <p class="mb-1"><i class="fas fa-phone mr-1 text-gray-500"></i>
                                            {{ $mitra->kontak_telepon ?? '-' }}</p>
                                        <p class="mb-1"><i class="fas fa-envelope mr-1 text-gray-500"></i>
                                            {{ $mitra->kontak_email ?? '-' }}</p>
                                        @if ($mitra->website)
                                            <a href="{{ $mitra->website }}" target="_blank"
                                                class="text-blue-400 hover:underline mt-2 inline-block">
                                                Website <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400">Belum ada mitra perusahaan yang terdaftar.</p>
                    @endforelse
                </div>
            </div>
            <button class="carousel-control-next absolute right-[-96px]" type="button"
                data-coreui-target="#carouselMitra" data-coreui-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-[#14151f] text-center py-6 text-sm text-gray-400">
        &copy; 2025 Remagang. All rights reserved.
    </footer>

</body>

</html>
