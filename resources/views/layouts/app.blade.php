<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Presensi - SDN 19 Kepahyang</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="font-sans bg-gray-50">
    @auth
        <!-- Admin Navbar -->
        <header class="bg-blue-800 text-white shadow-md sticky top-0 z-50">
            <nav class="container mx-auto px-6 py-3">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo SDN 19 Kepahyang" class="h-10">
                        <div>
                            <h1 class="text-lg font-bold">Sistem Presensi</h1>
                            <p class="text-xs">SDN 19 Kepahyang</p>
                        </div>
                    </div>
                 
                    <button class="md:hidden focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </nav>
        </header>
    @else
        <!-- Public Navbar -->
        <header class="bg-white shadow-md sticky top-0 z-50">
            <nav class="container mx-auto px-6 py-3">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo SDN 19 Kepahyang" class="h-12">
                        <div>
                            <h1 class="text-xl font-bold text-blue-800">SDN 19 Kepahyang</h1>
                            <p class="text-sm text-gray-600">Sistem Presensi Digital</p>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#home" class="text-blue-800 font-medium hover:text-blue-600">Beranda</a>
                        <a href="#features" class="text-gray-600 hover:text-blue-800">Fitur</a>
                        <a href="#login" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Login Admin</a>
                    </div>
                    <button class="md:hidden text-gray-600 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </nav>
        </header>
    @endauth

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-8">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">SDN 19 Kepahyang</h3>
                    <p class="mb-4">Jl. Pendidikan No. 19, Desa Kepahyang, Kec. Kota Agung, Kab. Tanggamus, Lampung</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Sistem Presensi Digital</h3>
                    <p>Solusi modern untuk manajemen kehadiran siswa berbasis web.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Kontak</h3>
                    <p class="mb-2"><i class="fas fa-phone-alt mr-2"></i> (0722) 1234567</p>
                    <p><i class="fas fa-envelope mr-2"></i> sdn19kepahyang@email.com</p>
                </div>
            </div>
            <div class="border-t border-blue-800 mt-8 pt-6 text-center">
                <p>&copy; 2023 SDN 19 Kepahyang. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>