<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman Buku</title>
    <style>
        body { font-family: sans-serif; color: #333; font-size: 11px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { margin: 0; text-transform: uppercase; font-size: 18px; }
        .header p { margin: 3px 0 0; color: #666; font-size: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px 5px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        
        .text-center { text-align: center; }
        .status { font-weight: bold; text-transform: uppercase; font-size: 8px; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Peminjaman Buku - PustakaKU</h1>
        <p>Jl. Perpustakaan No. 1, Kota UKK | Telp: (021) 1234567</p>
        <p>Data Peminjaman Keseluruhan</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 20px;">No</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali / Batas</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $i => $p)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $p->user->name }}</td>
                <td>{{ $p->book->judul }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_peminjaman)->format('d/m/Y') }}</td>
                <td>
                    @if($p->status_peminjaman === 'Kembali')
                        {{ \Carbon\Carbon::parse($p->tanggal_pengembalian)->format('d/m/Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d/m/Y') }} (Batas)
                    @endif
                </td>
                <td class="status">
                    {{ $p->status_peminjaman }}
                </td>
                <td>
                    @if($p->denda > 0)
                        Rp {{ number_format($p->denda, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }}</p>
        <br><br><br>
        <p>( ____________________ )</p>
        <p>Administrator</p>
    </div>
</body>
</html>
