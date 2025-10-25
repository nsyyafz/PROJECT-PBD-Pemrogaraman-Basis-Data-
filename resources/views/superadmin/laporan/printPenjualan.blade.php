<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
        }
        .info {
            margin-bottom: 20px;
        }
        .summary {
            background: #f0f0f0;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            width: 30%;
            margin-right: 3%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background-color: #333;
            color: white;
            padding: 8px;
            text-align: left;
        }
        td {
            padding: 6px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h2>LAPORAN PENJUALAN</h2>
        <p>Inventory System</p>
        <p>Periode: {{ date('d/m/Y', strtotime($tanggalDari)) }} - {{ date('d/m/Y', strtotime($tanggalSampai)) }}</p>
    </div>

    {{-- Ringkasan --}}
    <div class="summary">
        <div class="summary-item">
            <strong>Total Transaksi:</strong><br>
            {{ $totalTransaksi }} Transaksi
        </div>
        <div class="summary-item">
            <strong>Total Omset:</strong><br>
            Rp {{ number_format($totalOmset, 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Rata-rata:</strong><br>
            Rp {{ $totalTransaksi > 0 ? number_format($totalOmset / $totalTransaksi, 0, ',', '.') : 0 }}
        </div>
    </div>

    {{-- Barang Terlaris --}}
    <h3>Barang Terlaris</h3>
    <table>
        <thead>
            <tr>
                <th width="10%" class="text-center">#</th>
                <th width="50%">Nama Barang</th>
                <th width="20%" class="text-right">Total Terjual</th>
                <th width="20%" class="text-right">Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangTerlaris as $index => $barang)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td class="text-right">{{ number_format($barang->total_terjual, 0, ',', '.') }} {{ $barang->nama_satuan }}</td>
                <td class="text-right">Rp {{ number_format($barang->total_nilai, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Detail Transaksi --}}
    <h3>Detail Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th width="8%">No</th>
                <th width="12%">ID</th>
                <th width="25%">Tanggal</th>
                <th width="25%">Kasir</th>
                <th width="30%" class="text-right">Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penjualans as $index => $penjualan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $penjualan->idpenjualan }}</td>
                <td>{{ date('d-m-Y H:i', strtotime($penjualan->tanggal)) }}</td>
                <td>{{ $penjualan->kasir }}</td>
                <td class="text-right">Rp {{ number_format($penjualan->total_nilai, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada transaksi pada periode ini</td>
            </tr>
            @endforelse
        </tbody>
        @if(count($penjualans) > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL OMSET:</td>
                <td class="text-right">Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- Footer --}}
    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <br><br>
        <p>_______________________<br>
        Mengetahui</p>
    </div>

    {{-- Tombol Print --}}
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; cursor: pointer;">
            🖨️ Cetak Laporan
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px; cursor: pointer; margin-left: 10px;">
            ✖️ Tutup
        </button>
    </div>

    <script>
        // Auto print saat halaman dibuka (opsional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>