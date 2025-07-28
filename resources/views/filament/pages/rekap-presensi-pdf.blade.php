<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Presensi PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
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
    </style>
</head>

<body>
    <h2>Rekap Siswa Mata Pelajaran ({{ $filterJurusan }})</h2>
@if ($filterKelas)
    <p>Kelas: <strong>{{ $filterKelas }}</strong></p>
@endif

    <h2>Rekap Siswa Mata pelajaran ({{ $filterJurusan }})</h2>
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