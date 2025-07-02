@extends('layouts.app')

@section('content')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
        50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.8); }
    }
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-float { animation: float 3s ease-in-out infinite; }
    .animate-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
    .animate-gradient { animation: gradient-shift 4s ease infinite; background-size: 200% 200%; }
    .glass-effect { backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.1); }
    .text-shadow { text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); }
</style>

<!-- Hero Section with Particles Background -->
<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-800">
    <!-- Animated Background Particles -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-2 h-2 bg-yellow-300 rounded-full animate-ping"></div>
        <div class="absolute top-40 right-20 w-3 h-3 bg-blue-300 rounded-full animate-bounce"></div>
        <div class="absolute bottom-32 left-20 w-4 h-4 bg-green-300 rounded-full animate-pulse"></div>
        <div class="absolute top-60 left-1/3 w-2 h-2 bg-pink-300 rounded-full animate-ping animation-delay-1000"></div>
        <div class="absolute bottom-40 right-1/3 w-3 h-3 bg-purple-300 rounded-full animate-bounce animation-delay-2000"></div>
    </div>

    <!-- Main Hero Content -->
    <div class="relative z-10 container mx-auto px-4 py-24">
        <div class="flex flex-col-reverse lg:flex-row items-center gap-16">
            <!-- Left Content -->
            <div class="lg:w-1/2 space-y-8 text-center lg:text-left">
                <!-- Glowing Badge -->
                <div class="inline-flex items-center px-6 py-2 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 text-black font-bold text-sm animate-pulse-glow">
                    <i class="fas fa-star mr-2"></i>
                    Platform Presensi Terdepan
                </div>

                <!-- Main Title with Gradient -->
                <h1 class="text-5xl lg:text-7xl font-black leading-tight text-shadow">
                    <span class="bg-gradient-to-r from-yellow-300 via-pink-300 to-purple-300 bg-clip-text text-transparent animate-gradient">
                        Presensi Digital
                    </span>
                    <br>
                    <span class="text-white">Masa Depan</span>
                    <br>
                    <span class="bg-gradient-to-r from-blue-300 to-cyan-300 bg-clip-text text-transparent">
                        SDN 19 Kepahyang
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="text-xl lg:text-2xl text-blue-100 max-w-2xl leading-relaxed">
                    Revolusi sistem kehadiran dengan teknologi AI dan machine learning untuk pengalaman yang tak terlupakan ✨
                </p>

                <!-- Stats Counter -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-8 py-6">
                    <div class="text-center glass-effect rounded-xl p-4 border border-white/20">
                        <div class="text-3xl font-bold text-yellow-300">500+</div>
                        <div class="text-sm text-blue-200">Siswa Aktif</div>
                    </div>
                    <div class="text-center glass-effect rounded-xl p-4 border border-white/20">
                        <div class="text-3xl font-bold text-green-300">99.9%</div>
                        <div class="text-sm text-blue-200">Akurasi</div>
                    </div>
                    <div class="text-center glass-effect rounded-xl p-4 border border-white/20">
                        <div class="text-3xl font-bold text-pink-300">24/7</div>
                        <div class="text-sm text-blue-200">Online</div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <a href="{{ url('admin/login') }}" 
                       class="group relative px-8 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-300 hover:to-orange-400 text-black font-bold rounded-2xl transition-all transform hover:scale-105 hover:rotate-1 shadow-2xl hover:shadow-yellow-500/50">
                        <i class="fas fa-rocket mr-2 group-hover:animate-bounce"></i>
                        Mulai Sekarang
                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-xs text-white animate-ping">
                            <i class="fas fa-fire"></i>
                        </div>
                    </a>
                    <a href="#features" 
                       class="px-8 py-4 glass-effect border-2 border-white/30 text-white hover:bg-white/20 font-bold rounded-2xl transition-all hover:scale-105 group">
                        <i class="fas fa-play mr-2 group-hover:animate-pulse"></i>
                        Demo Interaktif
                    </a>
                </div>
            </div>

            <!-- Right Visual -->
            <div class="lg:w-1/2 relative">
                <!-- Floating Dashboard Preview -->
                <div class="relative animate-float">
                    <div class="absolute -inset-4 bg-gradient-to-r from-pink-500 to-purple-600 rounded-3xl blur-xl opacity-30 animate-pulse"></div>
                    <div class="relative bg-white/10 backdrop-blur-lg rounded-3xl border border-white/20 p-8 shadow-2xl">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full"></div>
                                <div class="w-20 h-3 bg-white/20 rounded-full"></div>
                            </div>
                            <div class="space-y-3">
                                <!-- Animated Progress Bars -->
                                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-green-400 to-blue-500 rounded-full animate-pulse" style="width: 85%"></div>
                                </div>
                                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full animate-pulse animation-delay-500" style="width: 92%"></div>
                                </div>
                                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-pink-400 to-purple-500 rounded-full animate-pulse animation-delay-1000" style="width: 78%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Floating Icons -->
                <div class="absolute -top-10 -right-10 w-16 h-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center animate-bounce shadow-lg">
                    <i class="fas fa-graduation-cap text-2xl text-white"></i>
                </div>
                <div class="absolute -bottom-10 -left-10 w-16 h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-2xl flex items-center justify-center animate-pulse shadow-lg">
                    <i class="fas fa-chart-line text-2xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <div class="w-6 h-10 border-2 border-white/50 rounded-full flex justify-center">
            <div class="w-1 h-3 bg-white/70 rounded-full mt-2 animate-pulse"></div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div id="features" class="relative py-24 bg-gradient-to-br from-slate-50 to-blue-50 overflow-hidden">
    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 w-full h-full">
        <div class="absolute top-20 left-10 w-32 h-32 bg-blue-200/20 rounded-full animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-48 h-48 bg-purple-200/20 rounded-full animate-float"></div>
    </div>

    <div class="relative container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <div class="inline-flex items-center px-6 py-2 rounded-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-sm mb-6">
                <i class="fas fa-magic mr-2"></i>
                Fitur Revolusioner
            </div>
            <h2 class="text-5xl lg:text-6xl font-black text-gray-800 mb-6">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Teknologi Terdepan
                </span>
                <br>
                untuk Masa Depan
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Nikmati pengalaman presensi yang tak pernah ada sebelumnya dengan fitur-fitur canggih yang akan mengubah cara pandang Anda
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            <!-- Feature 1 -->
            <div class="group relative bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-4 border border-gray-100 overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-qrcode text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-blue-600 transition-colors">QR Code Scanner</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Scan sekali, data tersimpan otomatis dengan teknologi AI recognition yang super akurat dan lightning fast!</p>
                    <div class="flex items-center text-blue-600 font-semibold group-hover:text-purple-600 transition-colors">
                        <span>Pelajari lebih lanjut</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="group relative bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-4 border border-gray-100 overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-green-400 to-blue-600 rounded-full opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-brain text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-green-600 transition-colors">AI Analytics</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Machine learning algorithm yang menganalisis pola kehadiran dan memberikan insight mendalam dengan visualisasi 3D!</p>
                    <div class="flex items-center text-green-600 font-semibold group-hover:text-blue-600 transition-colors">
                        <span>Jelajahi AI</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="group relative bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-4 border border-gray-100 overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-pink-400 to-red-600 rounded-full opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-bolt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-pink-600 transition-colors">Real-time Sync</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Sinkronisasi instant ke cloud dengan teknologi WebSocket, notifikasi push real-time, dan backup otomatis!</p>
                    <div class="flex items-center text-pink-600 font-semibold group-hover:text-red-600 transition-colors">
                        <span>Rasakan kecepatan</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="group relative bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-4 border border-gray-100 overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-full opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-mobile-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-yellow-600 transition-colors">PWA Mobile App</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Progressive Web App dengan offline mode, push notifications, dan native app experience di semua device!</p>
                    <div class="flex items-center text-yellow-600 font-semibold group-hover:text-orange-600 transition-colors">
                        <span>Download gratis</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </div>

            <!-- Feature 5 -->
            <div class="group relative bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-4 border border-gray-100 overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-full opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-shield-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-indigo-600 transition-colors">Blockchain Security</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Keamanan tingkat militer dengan enkripsi blockchain, biometric authentication, dan zero-trust architecture!</p>
                    <div class="flex items-center text-indigo-600 font-semibold group-hover:text-purple-600 transition-colors">
                        <span>Keamanan terjamin</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </div>

            <!-- Feature 6 -->
            <div class="group relative bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-4 border border-gray-100 overflow-hidden">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-teal-400 to-cyan-600 rounded-full opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <i class="fas fa-rocket text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-teal-600 transition-colors">IoT Integration</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Integrasi dengan smart devices, facial recognition cameras, dan sensor IoT untuk automasi complete ecosystem!</p>
                    <div class="flex items-center text-teal-600 font-semibold group-hover:text-cyan-600 transition-colors">
                        <span>Masa depan IoT</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="relative py-24 bg-gradient-to-r from-blue-900 via-purple-900 to-pink-900 overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-4 h-4 bg-yellow-300 rounded-full animate-ping"></div>
        <div class="absolute top-20 right-20 w-6 h-6 bg-blue-300 rounded-full animate-bounce"></div>
        <div class="absolute bottom-20 left-20 w-8 h-8 bg-green-300 rounded-full animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-3 h-3 bg-pink-300 rounded-full animate-ping"></div>
    </div>

    <div class="relative container mx-auto px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-5xl lg:text-6xl font-black text-white mb-8 text-shadow">
                Siap Mengubah
                <br>
                <span class="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent animate-gradient">
                    Masa Depan Pendidikan?
                </span>
            </h2>
            <p class="text-xl text-blue-100 mb-12 leading-relaxed">
                Bergabunglah dengan revolusi digital pendidikan dan rasakan pengalaman presensi yang tak terlupakan!
            </p>
            
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                <a href="{{ url('admin/login') }}" 
                   class="group px-12 py-5 bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-300 hover:to-orange-400 text-black font-bold text-lg rounded-full transition-all transform hover:scale-110 hover:rotate-2 shadow-2xl hover:shadow-yellow-500/50">
                    <i class="fas fa-rocket mr-3 group-hover:animate-bounce"></i>
                    Mulai Revolusi Sekarang
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-sm text-white animate-pulse">
                        🔥
                    </div>
                </a>
                <div class="text-blue-200 text-sm">
                    <i class="fas fa-users mr-2"></i>
                    Dipercaya oleh 10,000+ sekolah di Indonesia
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Add scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all feature cards
    document.querySelectorAll('.group').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(50px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
</script>
@endsection