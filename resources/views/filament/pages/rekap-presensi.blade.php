<x-filament::page>
    <h2 class="text-lg font-bold mb-4">Rekap Gabungan Siswa & Matkul</h2>

    {{-- Filter Jurusan --}}
    <div class="mb-4 w-1/3">
        <label for="filterJurusan" class="block text-sm font-medium text-gray-700 mb-1">Filter Jurusan</label>
        <select wire:model="filterJurusan" id="filterJurusan"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
            <option value="">- Semua Jurusan -</option>
            @foreach ($this->jurusanList as $jurusan)
                <option value="{{ $jurusan }}">{{ $jurusan }}</option>
            @endforeach
        </select>
    </div>

    {{-- Filter Kelas --}}
    <div class="mb-4 w-1/3">
        <label for="filterKelas" class="block text-sm font-medium text-gray-700 mb-1">Filter Kelas</label>
        <select wire:model="filterKelas" id="filterKelas"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
            <option value="">- Semua Kelas -</option>
            @foreach ($this->kelasList as $kelas)
                <option value="{{ $kelas->name }}">{{ $kelas->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <button wire:click="downloadPdf" type="button"
            class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
            Download PDF
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Kelas</th>
                    <th class="px-4 py-2 border">Matkul</th>
                    <th class="px-4 py-2 border">Hadir</th>
                    <th class="px-4 py-2 border">Izin</th>
                    <th class="px-4 py-2 border">Sakit</th>
                    <th class="px-4 py-2 border">Alpa</th>
                    <th class="px-4 py-2 border">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->rekapSiswaGabung as $row)
                    <tr>
                        <td class="px-4 py-2 border">{{ $row['nama'] }}</td>
                        <td class="px-4 py-2 border">{{ $row['kelas'] }}</td>
                        <td class="px-4 py-2 border">{{ $row['matkul'] }}</td>
                        <td class="px-4 py-2 border text-center">{{ $row['hadir'] }}</td>
                        <td class="px-4 py-2 border text-center">{{ $row['izin'] }}</td>
                        <td class="px-4 py-2 border text-center">{{ $row['sakit'] }}</td>
                        <td class="px-4 py-2 border text-center">{{ $row['alpa'] }}</td>
                        <td class="px-4 py-2 border text-center">{{ $row['total'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-gray-500">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament::page>
