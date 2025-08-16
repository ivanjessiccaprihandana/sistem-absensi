@extends('layouts.app')

@section('content')
{{-- Menghapus blok <style> karena styling akan menggunakan kelas Tailwind langsung --}}

<!-- Hero Section with Enhanced Visual Effects -->
<div class="relative min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-blue-800 text-white overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0">
        <!-- Floating geometric shapes -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full animate-pulse"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-yellow-400 bg-opacity-20 rounded-lg rotate-45 animate-bounce"></div>
        <div class="absolute bottom-40 left-1/4 w-12 h-12 bg-pink-400 bg-opacity-20 rounded-full animate-ping"></div>
        <div class="absolute bottom-20 right-1/3 w-24 h-24 bg-green-400 bg-opacity-10 rounded-lg rotate-12 animate-pulse"></div>
        
        <!-- Grid pattern overlay -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 25px 25px, rgba(255,255,255,0.2) 2px, transparent 0); background-size: 50px 50px;"></div>
    </div>
    
    <!-- Main Hero Content -->
    <div class="relative z-10 flex items-center min-h-screen">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="text-center lg:text-left">
                        <!-- Badge -->
                        <div class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm font-medium mb-6 backdrop-blur-sm border border-white border-opacity-30">
                            <i class="fas fa-star text-yellow-400 mr-2"></i>
                            Sistem Terdepan
                        </div>
                        
                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold leading-tight mb-6">
                            <span class="bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">Presensi Digital</span>
                            <br>
                            <span class="text-white">Modern</span>
                        </h1>
                        
                        <div class="flex items-center justify-center lg:justify-start mb-8">
                            <div class="h-1 w-20 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full mr-4"></div>
                            <span class="text-xl font-medium text-blue-200">Sekolah Dasar Negri 19 Kepahiang</span>
                        </div>
                        
                        <p class="text-xl text-gray-200 mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
                            Revolusi cara pencatatan kehadiran dengan teknologi digital yang akurat, cepat, dan mudah digunakan untuk semua kalangan.
                        </p>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="{{ url('admin/login') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-2xl font-semibold text-lg shadow-2xl hover:shadow-blue-500/25 transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                                <i class="fas fa-rocket mr-3 text-xl group-hover:animate-bounce"></i>
                                Mulai Sekarang
                                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform duration-300"></i>
                            </a>

                        </div>
                        
                        <!-- Stats -->
                        <div class="flex justify-center lg:justify-start space-x-8 mt-12">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-white">100%</div>
                                <div class="text-sm text-gray-300">Akurat</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-white">24/7</div>
                                <div class="text-sm text-gray-300">Tersedia</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-white">⚡</div>
                                <div class="text-sm text-gray-300">Cepat</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Visual -->
                    <div class="relative">
                        <div class="relative z-10 bg-white bg-opacity-10 backdrop-blur-lg rounded-3xl p-8 border border-white border-opacity-20 shadow-2xl">
                            <!-- Mock Dashboard Preview -->
                            <div class="bg-white rounded-2xl p-6 shadow-lg">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg mr-3"></div>
                                    <h3 class="text-gray-800 font-semibold">Informasi Terkini</h3>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                        <span class="text-gray-700">Total Guru</span>
                                        <span class="font-bold text-green-600">9</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                        <span class="text-gray-700">Total Siswa</span>
                                        <span class="font-bold text-blue-600">240</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                                        <span class="text-gray-700">Kelas Aktif</span>
                                        <span class="font-bold text-purple-600">6</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Decorative elements -->
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full opacity-20 animate-pulse"></div>
                        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-r from-pink-400 to-red-500 rounded-full opacity-20 animate-bounce"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <div class="w-6 h-10 border-2 border-white border-opacity-50 rounded-full flex justify-center">
            <div class="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
        </div>
    </div>
</div>

<!-- Features Section with Enhanced Cards -->
<section id="features" class="bg-gradient-to-b from-gray-50 to-white py-24">
    <div class="container mx-auto px-6">
        <!-- Section Header -->
        <div class="max-w-4xl mx-auto text-center mb-20">
            <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-gem mr-2"></i>
                Fitur Unggulan
            </div>
            <h2 class="text-4xl md:text-5xl font-bold mb-6">
                Solusi <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Terlengkap</span>
            </h2>
            <p class="text-xl text-gray-600 leading-relaxed">
                Sistem presensi digital dengan teknologi terdepan untuk kemudahan maksimal
            </p>
        </div>
        
        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            <!-- Feature 1 -->
            <div class="group bg-white p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 ease-in-out transform hover:-translate-y-2 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full -translate-y-16 translate-x-16 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800 group-hover:text-blue-600 transition-colors duration-300">Presensi Guru Digital</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Interface intuitif memungkinkan guru melakukan presensi siswa dengan cepat dan akurat per kelas atau per mata pelajaran.
                    </p>
                    <div class="flex items-center text-blue-600 font-medium group-hover:translate-x-2 transition-transform duration-300">
                        <span></span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </div>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="group bg-white p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 ease-in-out transform hover:-translate-y-2 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full -translate-y-16 translate-x-16 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-file-pdf text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800 group-hover:text-green-600 transition-colors duration-300">Rekap PDF Otomatis</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Sistem menghasilkan laporan presensi dalam format PDF profesional dengan template yang dapat disesuaikan.
                    </p>
                    <div class="flex items-center text-green-600 font-medium group-hover:translate-x-2 transition-transform duration-300">
                        <span></span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </div>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="group bg-white p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 ease-in-out transform hover:-translate-y-2 border border-gray-100 relative overflow-hidden md:col-span-2 lg:col-span-1">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full -translate-y-16 translate-x-16 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-analytics text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800 group-hover:text-purple-600 transition-colors duration-300">Analisis Real-time</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Dashboard analytics dengan visualisasi data kehadiran, statistik kelas, dan tren presensi untuk insights mendalam.
                    </p>
                    <div class="flex items-center text-purple-600 font-medium group-hover:translate-x-2 transition-transform duration-300">
                        <span></span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="bg-gradient-to-r from-blue-900 via-purple-900 to-indigo-900 py-24 text-white relative overflow-hidden">
    <!-- Background decorations -->
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-40 h-40 bg-white bg-opacity-5 rounded-full"></div>
        <div class="absolute bottom-10 right-10 w-60 h-60 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full opacity-10"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-white bg-opacity-3 rounded-full"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <!-- Section Header -->
        <div class="max-w-4xl mx-auto text-center mb-20">
            <div class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm text-white rounded-full text-sm font-medium mb-4 border border-white border-opacity-30">
                <i class="fas fa-cogs mr-2"></i>
                Cara Kerja
            </div>
            <h2 class="text-4xl md:text-5xl font-bold mb-6">
                Mudah dalam <span class="bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent">3 Langkah</span>
            </h2>
            <p class="text-xl text-gray-200 leading-relaxed">
                Proses sederhana yang dapat dipahami semua kalangan
            </p>
        </div>
        
        <!-- Steps -->
        <div class="grid md:grid-cols-3 gap-12 max-w-6xl mx-auto relative">
            {{-- Connection Lines --}}
            <div class="hidden md:block absolute top-[100px] left-1/2 transform -translate-x-1/2 h-1 bg-white bg-opacity-30 w-[calc(100%-240px)]"></div>
            
            <!-- Step 1 -->
            <div class="relative text-center group">
                <div class="relative mb-8">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center mx-auto text-3xl font-bold shadow-2xl border-4 border-white border-opacity-30 backdrop-blur-sm group-hover:scale-110 transition-all duration-300 z-10">
                        1
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-yellow-400 rounded-full animate-ping"></div>
                </div>
                
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 border border-white border-opacity-20 group-hover:bg-opacity-20 transition-all duration-300 flex flex-col items-center justify-between h-[200px]"> {{-- Added flexbox for better alignment --}}
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-sign-in-alt text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Login Aman</h3>
                    <p class="text-gray-200 leading-relaxed">
                        Akses sistem dengan kredensial yang telah terdaftar dengan keamanan tingkat tinggi
                    </p>
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="relative text-center group">
                <div class="relative mb-8">
                    <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-full flex items-center justify-center mx-auto text-3xl font-bold shadow-2xl border-4 border-white border-opacity-30 backdrop-blur-sm group-hover:scale-110 transition-all duration-300 z-10">
                        2
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-green-400 rounded-full animate-ping animation-delay-150"></div>
                </div>
                
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 border border-white border-opacity-20 group-hover:bg-opacity-20 transition-all duration-300 flex flex-col items-center justify-between h-[200px]"> {{-- Added flexbox for better alignment --}}
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Catat Kehadiran</h3>
                    <p class="text-gray-200 leading-relaxed">
                        Input presensi siswa dengan interface yang responsif dan user-friendly
                    </p>
                </div>
            </div>
            
            <!-- Step 3 -->
            <div class="relative text-center group">
                <div class="relative mb-8">
                    <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-full flex items-center justify-center mx-auto text-3xl font-bold shadow-2xl border-4 border-white border-opacity-30 backdrop-blur-sm group-hover:scale-110 transition-all duration-300 z-10">
                        3
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-pink-400 rounded-full animate-ping animation-delay-300"></div>
                </div>
                
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 border border-white border-opacity-20 group-hover:bg-opacity-20 transition-all duration-300 flex flex-col items-center justify-between h-[200px]"> {{-- Added flexbox for better alignment --}}
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-download text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Ekspor Laporan</h3>
                    <p class="text-gray-200 leading-relaxed">
                        Unduh rekap presensi dalam format PDF siap cetak untuk administrasi
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Custom CSS for animations -->
<style>
@keyframes fade-in-down {
    0% {
        opacity: 0;
        transform: translateY(-30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in-up {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-down {
    animation: fade-in-down 1s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 1s ease-out;
}

.delay-100 {
    animation-delay: 0.1s;
}

.delay-200 {
    animation-delay: 0.2s;
}

.delay-300 {
    animation-delay: 0.3s;
}

.animation-delay-150 {
    animation-delay: 150ms;
}

.animation-delay-300 {
    animation-delay: 300ms;
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}
</style>

@endsection
