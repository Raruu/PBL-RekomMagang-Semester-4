<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Remagang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Pacifico&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .font-script {
            font-family: 'Pacifico', cursive;
        }

        /* Custom scrollbar for horizontal scrolling */
        .scroll-container {
            scrollbar-width: thin;
            scrollbar-color: #4b5563 #1a1c29;
        }

        .scroll-container::-webkit-scrollbar {
            height: 8px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: #1a1c29;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background-color: #4b5563;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-[#1a1c29] text-white">

    <!-- Navbar -->
    <header class="flex justify-between items-center px-6 py-3 border-b border-gray-700 bg-[#1a1c29]">
        <div class="text-gray-400 text-sm">Beranda Utama</div>
        <div class="font-script text-white text-xl">Remagang</div>
        <a href="{{ route('login') }}">
            <button
                class="bg-white text-[#1a1c29] px-4 py-1 rounded text-sm font-semibold hover:opacity-90 transition">Masuk</button>
        </a>
    </header>

    <!-- Hero Section -->
    <section class="relative">
        <img src="https://storage.googleapis.com/a1aa/image/acdda924-f6b6-42b1-fa50-a9842116fab6.jpg"
            alt="Modern glass buildings" class="w-full h-[26rem] object-cover brightness-[0.4]">
        <div class="absolute inset-0 flex flex-col justify-center items-start px-6 md:px-12 max-w-3xl">
            <h1 class="text-3xl md:text-5xl font-bold leading-tight mb-3">Selamat Datang di Remagang</h1>
            <p class="text-base md:text-lg italic font-semibold">Rekomendasi Magang Mahasiswa Yang Tepat dan Terpercaya
            </p>
            <p class="text-sm md:text-base mt-2 max-w-md leading-relaxed">Remagang hadir sebagai solusi cerdas untuk
                menemukan tempat magang sesuai minat, jurusan, dan keahlian mahasiswa.</p>
            <div class="mt-5 flex gap-4">
                <a href="{{ route('login') }}">
                    <button
                        class="border border-white px-5 py-2 rounded hover:bg-white hover:text-[#1a1c29] transition">Masuk</button>
                </a>
                <a href="{{ route('register') }}">
                    <button
                        class="border border-white px-5 py-2 rounded hover:bg-white hover:text-[#1a1c29] transition">Daftar</button>
                </a>
            </div>
        </div>
    </section>

    <!-- Tentang Remagang -->
    <section class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-2xl font-bold mb-4">Apa itu Sistem Remagang?</h2>
            <p class="text-sm font-light leading-relaxed">Platform digital yang membantu mahasiswa mendapatkan
                rekomendasi magang secara personal dan akurat. Didukung oleh data dan kemitraan dengan berbagai
                perusahaan, kami bantu kamu melangkah lebih dekat ke dunia kerja.</p>
        </div>
        <div class="flex justify-center">
            <div
                class="w-48 h-48 rounded-full bg-gradient-to-tr from-blue-400 to-purple-1000 flex justify-center items-center">
                <img src="{{ asset('imgs/logo.webp') }}" alt="Logo" class="w-48 h-48 object-contain"
                    id="picture-preview">

            </div>
        </div>

    </section>

    <!-- Cara Penggunaan -->
    <section class="bg-[#222430] py-16">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
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
            <div>
                <img src="https://storage.googleapis.com/a1aa/image/8570178c-9fb0-4b4e-51a2-cadc30ab751d.jpg"
                    alt="usage illustration" class="w-full max-w-md mx-auto">
            </div>
        </div>
    </section>

    <!-- Perusahaan Mitra -->
   <!-- Perusahaan Mitra -->
<section class="max-w-7xl mx-auto px-6 py-16">
    <h3 class="text-2xl font-bold mb-6">Perusahaan Mitra</h3>

    <div class="relative">
        <!-- Tombol Navigasi -->
        <button id="scrollLeft"
            class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-[#2e2f40] hover:bg-[#3a3c52] text-white p-2 rounded-full z-10 shadow-md">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button id="scrollRight"
            class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-[#2e2f40] hover:bg-[#3a3c52] text-white p-2 rounded-full z-10 shadow-md">
            <i class="fas fa-chevron-right"></i>
        </button>

        <!-- Container Scroll -->
        <div id="scrollContainer"
            class="flex overflow-x-auto space-x-6 scroll-smooth px-2 py-4 scroll-container">
            @forelse ($perusahaan as $mitra)
                <div
                    class="min-w-[280px] max-w-xs flex-shrink-0 bg-[#222430] rounded-xl p-4 border border-gray-700 text-sm text-white">
                    <h4 class="text-lg font-bold mb-2">{{ $mitra->nama_perusahaan }}</h4>
                    <p class="text-gray-400 mb-1"><i class="fas fa-map-marker-alt mr-1 text-gray-500"></i>
                        {{ $mitra->lokasi->alamat ?? '-' }}
                    </p>
                    <p class="mb-1"><i class="fas fa-industry mr-1 text-gray-500"></i>
                        {{ $mitra->bidangIndustri->nama ?? 'Belum ada bidang' }}
                    </p>
                    <p class="mb-1"><i class="fas fa-phone mr-1 text-gray-500"></i> {{ $mitra->kontak_telepon ?? '-' }}</p>
                    <p class="mb-1"><i class="fas fa-envelope mr-1 text-gray-500"></i> {{ $mitra->kontak_email ?? '-' }}</p>
                    @if($mitra->website)
                        <a href="{{ $mitra->website }}" target="_blank"
                            class="text-blue-400 hover:underline mt-2 inline-block">
                            Website <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                        </a>
                    @endif
                </div>
            @empty
                <p class="text-gray-400">Belum ada mitra perusahaan yang terdaftar.</p>
            @endforelse
        </div>
    </div>
</section>


    <!-- Footer -->
    <footer class="bg-[#14151f] text-center py-6 text-sm text-gray-400">
        &copy; 2025 Remagang. All rights reserved.
    </footer>

</body>

</html>