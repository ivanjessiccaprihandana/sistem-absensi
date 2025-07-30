<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Presensi PDF</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 15px; /* Added some margin for better spacing */
        }

        th,
        td {
            border: 1px solid #333;
            padding: 4px;
            text-align: center;
        }

        th {
            background: #eee;
        }

        h2 {
            margin-bottom: 5px; /* Adjust spacing for headings */
        }

        p {
            margin-bottom: 3px; /* Adjust spacing for paragraphs */
        }
    </style>
</head>

<body>
    <h2>Rekap Siswa Mata Pelajaran ({{ $filterJurusan }})</h2>
    @if ($filterKelas)
        <p>Kelas: <strong>{{ $filterKelas }}</strong></p>
    @endif

    {{-- Menambahkan informasi pertemuan di sini --}}
    <p>Pertemuan: <strong>{{ $namaPertemuan }}</strong></p>

    {{-- Judul ini mungkin duplikat, Anda bisa menghapusnya jika tidak diperlukan --}}
    {{-- <h2>Rekap Siswa Mata pelajaran ({{ $filterJurusan }})</h2> --}}
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Matkul</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpa</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $row)
            <tr>
                <td>{{ $row['nama'] }}</td>
                <td>{{ $row['kelas'] }}</td>
                <td>{{ $row['matkul'] }}</td>
                <td>{{ $row['hadir'] }}</td>
                <td>{{ $row['izin'] }}</td>
                <td>{{ $row['sakit'] }}</td>
                <td>{{ $row['alpa'] }}</td>
                <td>{{ $row['total'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
