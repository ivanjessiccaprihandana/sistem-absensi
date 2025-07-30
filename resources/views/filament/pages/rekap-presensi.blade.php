<x-filament::page>
    <div class="space-y-6">
        <h2 class="text-lg font-bold mb-4">Rekap Gabungan Siswa & Matkul</h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Filter Jurusan --}}
            <div>
                <label for="filterJurusan" class="block mb-1 font-semibold text-sm">Jurusan</label>
                {{-- Menggunakan wire:model.live agar perubahan langsung memicu update --}}
                <select wire:model.live="filterJurusan" id="filterJurusan"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">- Pilih Jurusan -</option>
                    {{-- Mengambil daftar jurusan secara dinamis dari properti jurusanOptions --}}
                    @foreach ($this->jurusanOptions as $jurusan)
                        <option value="{{ $jurusan }}">{{ $jurusan }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Kelas --}}
            <div>
                <label for="filterKelas" class="block mb-1 font-semibold text-sm">Kelas</label>
                {{-- Menggunakan wire:model.live agar perubahan langsung memicu update --}}
                <select wire:model.live="filterKelas" id="filterKelas"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">- Pilih Kelas -</option>
                    {{-- Mengambil daftar kelas secara dinamis dari properti komputasi getKelasListProperty --}}
                    @foreach ($this->kelasList as $kelas)
                        <option value="{{ $kelas->name }}">{{ $kelas->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Pertemuan --}}
            <div>
                <label for="filterPertemuan" class="block mb-1 font-semibold text-sm">Pertemuan</label>
                {{-- Menggunakan wire:model.live agar perubahan langsung memicu update --}}
                <select wire:model.live="filterPertemuan" id="filterPertemuan"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">- Semua Pertemuan -</option>
                    {{-- MENGGANTI $meeting->pertemuan_ke dengan $meeting->NAMA_KOLOM_YANG_BENAR --}}
                    @foreach ($this->meetingList as $meeting)
                        <option value="{{ $meeting->id }}">Pertemuan ke-{{ $meeting->meeting_number }}</option> {{-- Ganti 'meeting_number' dengan nama kolom yang benar --}}
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Tombol Download PDF --}}
        <div class="flex justify-end">
            <x-filament::button wire:click="downloadPdf"
                class="px-4 py-2 bg-primary-600 text-white rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                Download PDF
            </x-filament::button>
        </div>

        {{-- Tabel Rekap --}}
        <div class="overflow-x-auto bg-white rounded-md shadow-sm">
            <table class="min-w-full text-sm text-left border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 border-b border-gray-200 font-semibold text-gray-700">Nama</th>
                        <th class="px-4 py-3 border-b border-gray-200 font-semibold text-gray-700">Kelas</th>
                        <th class="px-4 py-3 border-b border-gray-200 font-semibold text-gray-700">Matkul</th>
                        <th class="px-4 py-3 border-b border-gray-200 font-semibold text-gray-700">Hadir</th>
                        <th class="px-4 py-3 border-b border-gray-200 font-semibold text-gray-700">Izin</th>
                        <th class="px-4 py-3 border-b border-gray-200 font-semibold text-gray-700">Sakit</th>
                        <th class="px-4 py-3 border-b border-gray-200 font-semibold text-gray-700">Alpa</th>
                        <th class="px-4 py-3 border-b border-gray-200 font-semibold text-gray-700">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->rekapSiswaGabung as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b border-gray-200">{{ $row['nama'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-200">{{ $row['kelas'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-200">{{ $row['matkul'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-200 text-center">{{ $row['hadir'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-200 text-center">{{ $row['izin'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-200 text-center">{{ $row['sakit'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-200 text-center">{{ $row['alpa'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-200 text-center">{{ $row['total'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-4 border-b border-gray-200 text-center text-gray-500 italic">Tidak ada data presensi. Silakan pilih Jurusan dan Kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament::page>
