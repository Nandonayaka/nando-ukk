<!DOCTYPE html>
<html>
<head>
    <title>Bukti Peminjaman #{{ $peminjaman->id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; text-transform: uppercase; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 14px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .info-table th, .info-table td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        .info-table th { width: 30%; background-color: #f9f9f9; font-weight: bold; color: #000; text-transform: uppercase; font-size: 12px; }
        .info-table td { font-size: 14px; }
        .status { padding: 4px 10px; border-radius: 4px; font-weight: bold; font-size: 12px; display: inline-block; }
        .status-pinjam { background: #fff3e0; color: #e65100; }
        .status-kembali { background: #e8f5e9; color: #2e7d32; }
        .footer { margin-top: 50px; text-align: right; font-size: 12px; color: #666; }
        .footer p { margin: 5px 0; }
        .stamp { margin-top: 20px; font-weight: bold; color: #000; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PustakaKU</h1>
        <p>Laporan Bukti Peminjaman Buku Digital</p>
        <p>Jl. Perpustakaan No. 1, Kota UKK</p>
    </div>

    <table class="info-table">
        <tr>
            <th>ID Transaksi</th>
            <td>#{{ $peminjaman->id }}</td>
        </tr>
        <tr>
            <th>Peminjam</th>
            <td>{{ $peminjaman->user->name }}</td>
        </tr>
        <tr>
            <th>Buku</th>
            <td>{{ $peminjaman->book->judul }}</td>
        </tr>
        <tr>
            <th>Penulis</th>
            <td>{{ $peminjaman->book->penulis }}</td>
        </tr>
        <tr>
            <th>Tanggal Pinjam</th>
            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Batas Kembali</th>
            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_jatuh_tempo)->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if($peminjaman->status_peminjaman === 'Pinjam')
                    <span class="status status-pinjam">SEDANG DIPINJAM</span>
                @elseif($peminjaman->status_peminjaman === 'Kembali')
                    <span class="status status-kembali">SUDAH DIKEMBALIKAN</span>
                @else
                    <span class="status">{{ $peminjaman->status_peminjaman }}</span>
                @endif
            </td>
        </tr>
        @if($peminjaman->denda > 0)
        <tr>
            <th>Denda</th>
            <td style="color: #d32f2f; font-weight: bold;">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }} ({{ $peminjaman->status_denda }})</td>
        </tr>
        @endif
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }}</p>
        <p>Petugas Perpustakaan,</p>
        <br><br><br>
        <p class="stamp">( ____________________ )</p>
        <p>PustakaKU System</p>
    </div>
</body>
</html>
