<x-filament::page>
    <div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- ✨ Fancy Header -->
     <div class="bg-white rounded-2xl shadow-xl p-8 text-gray-900 animate-fade-in">
    <h1 class="text-4xl font-extrabold tracking-tight">📘 Presensi Siswa SD</h1>
    <p class="mt-2 text-gray-600 text-lg">Pantau kehadiran siswa dengan mudah dan menyenangkan 🚀</p>
</div>


        <!-- 🎨 Filter Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in">
            <!-- Kelas -->
            <x-filament::card class="border border-yellow-200 bg-gradient-to-br from-yellow-100 to-yellow-50 hover:shadow-lg transition-shadow">
                <label for="class" class="block text-sm font-semibold text-yellow-900 mb-2">🎓 Pilih Kelas</label>
                <select wire:model="selectedClass" id="class"
                        class="w-full border-yellow-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </x-filament::card>

            <!-- Mapel -->
            <x-filament::card class="border border-blue-200 bg-gradient-to-br from-blue-100 to-blue-50 hover:shadow-lg transition-shadow">
                <label for="subject" class="block text-sm font-semibold text-blue-900 mb-2">📖 Mata Pelajaran</label>
                <select wire:model="selectedSubject" id="subject"
                        class="w-full border-blue-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->nama }}</option>
                    @endforeach
                </select>
            </x-filament::card>

            <!-- Pertemuan -->
            <x-filament::card class="border border-green-200 bg-gradient-to-br from-green-100 to-green-50 hover:shadow-lg transition-shadow">
                <label for="meeting" class="block text-sm font-semibold text-green-900 mb-2">📅 Pertemuan</label>
                <select wire:model="selectedMeeting" id="meeting"
                        class="w-full border-green-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                    <option value="">-- Pilih Pertemuan --</option>
                    @foreach ($meetings as $meeting)
                        @if ($meeting->subject_id == $selectedSubject)
                            <option value="{{ $meeting->meeting_number }}">Pertemuan ke-{{ $meeting->meeting_number }}</option>
                        @endif
                    @endforeach
                </select>
            </x-filament::card>
        </div>

        <!-- 🧾 Tabel Presensi -->
        @if ($selectedMeeting && $selectedSubject)
            <form wire:submit.prevent="save" class="animate-fade-in-up">
                <div class="rounded-2xl shadow-xl overflow-hidden border border-purple-200 bg-white">
                   <div class="px-6 py-4 border-b bg-white text-gray-900">
    <h2 class="text-xl font-bold">📋 Daftar Presensi</h2>
    <p class="text-sm text-gray-600">Pertemuan ke-{{ $selectedMeeting }} - {{ $subjects->firstWhere('id', $selectedSubject)->nama }}</p>
</div>


                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-purple-200 text-sm">
                            <thead class="bg-purple-50">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-purple-800">No</th>
                                    <th class="px-6 py-3 text-left font-semibold text-purple-800">Nama Siswa</th>
                                    <th class="px-6 py-3 text-left font-semibold text-purple-800">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-purple-100">
                                @foreach ($students as $index => $student)
                                    <tr class="hover:bg-purple-50 transition-all duration-150">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $student->name }}</td>
                                        <td class="px-6 py-4">
                                            <select wire:model="attendance.{{ $student->id }}"
                                                    class="rounded-md shadow-sm py-1 px-2 transition-all duration-200
                                                    {{ match($attendance[$student->id]) {
                                                        'hadir' => 'bg-green-50 text-green-800 border-green-300 focus:ring-green-500',
                                                        'izin' => 'bg-yellow-50 text-yellow-800 border-yellow-300 focus:ring-yellow-500',
                                                        'alpa' => 'bg-red-50 text-red-800 border-red-300 focus:ring-red-500',
                                                        default => 'border-gray-300'
                                                    } }}">
                                                <option value="hadir">✅ Hadir</option>
                                                <option value="izin">🟡 Izin</option>
                                                <option value="alpa">❌ Tidak Hadir</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t bg-purple-50 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold rounded-lg shadow hover:scale-105 transform transition">
                            💾 Simpan Presensi
                        </button>
                    </div>
                </div>
            </form>
        @else
            <div class="bg-gradient-to-br from-indigo-50 to-purple-100 rounded-xl p-10 text-center border-2 border-dashed border-purple-200 animate-fade-in">
                <h3 class="text-xl font-semibold text-purple-800">Belum ada data yang dipilih</h3>
                <p class="text-sm text-purple-600 mt-1">Silakan pilih kelas, mata pelajaran, dan pertemuan terlebih dahulu</p>
                <div class="mt-4 animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </div>
        @endif
    </div>
</x-filament::page>
