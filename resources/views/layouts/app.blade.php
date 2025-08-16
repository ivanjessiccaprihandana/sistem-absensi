<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Sistem Presensi Digital</title> <!-- Judul umum untuk layout -->

    <link rel="icon" href="{{ asset('images/icon-sekolah.png') }}" type="image/png">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome CDN for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif; /* Menggunakan Inter */
            color: #2d3748;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <!-- Logo/Nama Aplikasi -->
            <a href="/" class="flex items-center space-x-2 text-blue-600 font-bold text-xl">
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SDN 19 Kepahiang" class="rounded-full h-10 w-10">
                <span>Sekolah Dasar Negeri 19 Kepahiang</span>
            </a>

            <!-- Navigasi Utama -->
            <div class="hidden md:flex space-x-6">
                <a href="/" class="text-gray-600 hover:text-blue-600 font-medium transition duration-300">Beranda</a>
                <a href="#features" class="text-gray-600 hover:text-blue-600 font-medium transition duration-300">Fitur</a>
                {{-- Anda bisa menambahkan link lain di sini, misalnya untuk login admin --}}
                {{-- <a href="{{ url('admin/login') }}" class="text-gray-600 hover:text-blue-600 font-medium transition duration-300">Login Admin</a> --}}
            </div>

            <!-- Tombol Hamburger untuk Mobile (jika diperlukan, bisa ditambahkan JavaScript) -->
            <div class="md:hidden">
                <button class="text-gray-600 hover:text-blue-600 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </nav>

    {{-- Ini adalah tempat di mana konten dari home.blade.php akan ditempatkan --}}
    @yield('content')

    <!-- Footer yang Diperbarui -->
    <footer class="bg-blue-800 text-white py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left max-w-5xl mx-auto">
                <!-- Kolom 1: Informasi Sekolah -->
                <div>
                    <h3 class="text-xl font-bold mb-4">SDN 19 Kepahiang</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        Imigrasi Permu, Kec. Kepahiang, Kabupaten Kepahiang, Bengkulu
                    </p>
                </div>

                <!-- Kolom 2: Deskripsi Sistem -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Sistem Presensi Digital</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        Solusi modern untuk manajemen kehadiran siswa berbasis web.
                    </p>
                </div>

                <!-- Kolom 3: Kontak -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Kontak</h3>
                    <ul class="text-gray-300 text-sm space-y-2">
                        <li class="flex items-center justify-center md:justify-start">
                            <i class="fas fa-phone-alt mr-2"></i>
                            <span>(0722) 1234567</span>
                        </li>
                        <li class="flex items-center justify-center md:justify-start">
                            <i class="fas fa-envelope mr-2"></i>
                            <span>sdn19kepahiang@email.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Hak Cipta -->
            <div class="border-t border-blue-700 mt-10 pt-8 text-center text-gray-400 text-sm">
                &copy; 2025 SDN 19 Kepahiang. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
