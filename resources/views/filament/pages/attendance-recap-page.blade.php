<x-filament::page>
    <h1 class="text-2xl font-bold mb-4">Rekap Presensi Siswa</h1>
    <a href="{{ route('rekap.presensi.pdf') }}"
   target="_blank"
   class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
    Download Rekap Presensi (PDF)
</a>
    <table class="w-full table-auto border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Nama</th>
                <th class="border px-4 py-2">Hadir</th>
                <th class="border px-4 py-2">Izin</th>
                <th class="border px-4 py-2">Alpa</th>
                <th class="border px-4 py-2">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $data)
                <tr>
                    <td class="border px-4 py-2">{{ $data['name'] }}</td>
                    <td class="border px-4 py-2 text-green-600">{{ $data['hadir'] }}</td>
                    <td class="border px-4 py-2 text-yellow-600">{{ $data['izin'] }}</td>
                    <td class="border px-4 py-2 text-red-600">{{ $data['alpa'] }}</td>
                    <td class="border px-4 py-2">{{ $data['total'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament::page>
