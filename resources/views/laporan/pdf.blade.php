<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tahfidz - {{ $monthName }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #555; }
        .stats { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .stats td { padding: 8px; border: 1px solid #ddd; background: #f9f9f9; text-align: center; width: 25%; }
        .stats strong { display: block; font-size: 16px; margin-top: 5px; color: #111; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.data th, table.data td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        table.data th { background-color: #eee; font-weight: bold; }
        .progress-container { width: 100px; background-color: #ddd; height: 10px; border-radius: 5px; overflow: hidden; display: inline-block; vertical-align: middle; }
        .progress-bar { background-color: #4CAF50; height: 100%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 50px; text-align: right; font-size: 11px; color: #777; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Pencapaian Tahfidz Santri</h2>
        <p>Bulan: {{ $monthName }} | Kelas: {{ $kelas }}</p>
    </div>

    <table class="stats">
        <tr>
            <td>Total Santri<br><strong>{{ $totalSantri }}</strong></td>
            <td>Total Setoran<br><strong>{{ $totalSetoran }}</strong></td>
            <td>Rata-rata Nilai<br><strong>{{ $rataNilai }}</strong></td>
            <td>Progress Tercepat<br><strong>{{ $tercepat->santri->nama_lengkap ?? '-' }} ({{ $tercepat->target_juz ?? 0 }} Juz)</strong></td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">Rnk</th>
                <th width="25%">Nama Santri</th>
                <th width="15%">Kelas</th>
                <th width="25%">Ustadz Pengampu</th>
                <th width="20%">Progress</th>
                <th width="10%" class="text-right">Juz</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ranking as $index => $target)
            @php $pct = min(100, round(($target->target_juz / 30) * 100)); @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $target->santri->nama_lengkap ?? '-' }}</td>
                <td>{{ $target->santri->kelas ?? '-' }} {{ $target->santri->kelas_halaqah ?? '' }}</td>
                <td>{{ $target->santri->ustadz->nama_lengkap ?? '-' }}</td>
                <td>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: {{ $pct }}%;"></div>
                    </div>
                    <span style="font-size:10px; margin-left:5px">{{ $pct }}%</span>
                </td>
                <td class="text-right"><strong>{{ $target->target_juz }}</strong> / 30</td>
            </tr>
            @endforeach
            @if(count($ranking) === 0)
            <tr>
                <td colspan="6" class="text-center">Tidak ada data santri.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d M Y H:i') }}
    </div>

</body>
</html>
