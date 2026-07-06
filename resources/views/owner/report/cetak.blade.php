<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok — {{ $dariTanggal->format('d M Y') }} s/d {{ $sampaiTanggal->format('d M Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #1e293b; background: #fff; padding: 32px; }

        .header { text-align: center; border-bottom: 2px solid #1e293b; padding-bottom: 16px; margin-bottom: 24px; }
        .header h1 { font-size: 20px; font-weight: 800; }
        .header p { font-size: 12px; color: #64748b; margin-top: 4px; }

        .summary { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
        .summary-card { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; text-align: center; }
        .summary-card .label { font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; }
        .summary-card .value { font-size: 28px; font-weight: 800; margin-top: 4px; }
        .summary-card .sub { font-size: 11px; color: #94a3b8; margin-top: 2px; }
        .green { color: #16a34a; }
        .red { color: #dc2626; }

        .section { margin-bottom: 28px; }
        .section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 8px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px 6px 0 0; display: flex; align-items: center; gap: 8px; }
        .dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
        .dot-green { background: #16a34a; }
        .dot-red { background: #dc2626; }

        table { width: 100%; border-collapse: collapse; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 6px 6px; overflow: hidden; }
        th { background: #f1f5f9; padding: 8px 12px; text-align: left; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
        td { padding: 8px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }
        .mono { font-family: monospace; }
        .badge-green { background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 999px; font-weight: 700; font-size: 12px; font-family: monospace; }
        .badge-red { background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 999px; font-weight: 700; font-size: 12px; font-family: monospace; }
        .badge-ai { background: #ede9fe; color: #7c3aed; padding: 2px 8px; border-radius: 999px; font-size: 11px; }
        .badge-manual { background: #fef3c7; color: #d97706; padding: 2px 8px; border-radius: 999px; font-size: 11px; }
        .empty { padding: 24px; text-align: center; color: #94a3b8; font-size: 12px; }

        .footer { margin-top: 32px; text-align: center; font-size: 11px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 16px; }

        @media print {
            body { padding: 16px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    {{-- Tombol cetak (tidak muncul saat print) --}}
    <div class="no-print" style="text-align:right; margin-bottom: 16px;">
        <button onclick="window.print()"
            style="background:#2563eb; color:#fff; border:none; padding:8px 20px; border-radius:6px; font-weight:700; font-size:13px; cursor:pointer;">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()"
            style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; padding:8px 16px; border-radius:6px; font-weight:700; font-size:13px; cursor:pointer; margin-left:8px;">
            ✕ Tutup
        </button>
    </div>

    {{-- Header --}}
    <div class="header">
        <h1>📊 Laporan Stok Warung</h1>
        <p>Periode: {{ $dariTanggal->format('d M Y') }} — {{ $sampaiTanggal->format('d M Y') }}</p>
        <p>Dicetak: {{ now()->format('d M Y, H:i') }}</p>
    </div>

    {{-- Ringkasan --}}
    <div class="summary">
        <div class="summary-card">
            <div class="label">Total Stok Masuk</div>
            <div class="value green">{{ $stockIn->sum('jumlah') }}</div>
            <div class="sub">{{ $stockIn->count() }} transaksi</div>
        </div>
        <div class="summary-card">
            <div class="label">Total Stok Keluar</div>
            <div class="value red">{{ $stockOut->sum('jumlah') }}</div>
            <div class="sub">{{ $stockOut->count() }} transaksi</div>
        </div>
    </div>

    {{-- Stok Masuk --}}
    <div class="section">
        <div class="section-title">
            <span class="dot dot-green"></span>
            Stok Masuk
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Sumber</th>
                    <th>Keterangan</th>
                    <th style="text-align:right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stockIn as $i => $item)
                <tr>
                    <td class="mono" style="color:#94a3b8">{{ $i + 1 }}</td>
                    <td class="mono" style="font-size:12px">{{ $item->created_at->format('d M Y, H:i') }}</td>
                    <td><strong>{{ $item->product->nama_produk ?? '—' }}</strong></td>
                    <td style="text-transform:capitalize">{{ $item->sumber }}</td>
                    <td style="color:#64748b; font-size:12px">{{ $item->keterangan ?? '—' }}</td>
                    <td style="text-align:right">
                        <span class="badge-green">+{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="empty">Tidak ada data stok masuk pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Stok Keluar --}}
    <div class="section">
        <div class="section-title">
            <span class="dot dot-red"></span>
            Stok Keluar
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Tipe</th>
                    <th>Keterangan</th>
                    <th style="text-align:right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stockOut as $i => $item)
                <tr>
                    <td class="mono" style="color:#94a3b8">{{ $i + 1 }}</td>
                    <td class="mono" style="font-size:12px">{{ $item->created_at->format('d M Y, H:i') }}</td>
                    <td><strong>{{ $item->product->nama_produk ?? '—' }}</strong></td>
                    <td>
                        @if ($item->tipe === 'otomatis')
                            <span class="badge-ai">🤖 Kamera</span>
                        @else
                            <span class="badge-manual">✋ Manual</span>
                        @endif
                    </td>
                    <td style="color:#64748b; font-size:12px">{{ $item->keterangan ?? '—' }}</td>
                    <td style="text-align:right">
                        <span class="badge-red">-{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="empty">Tidak ada data stok keluar pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        StockVision — Laporan dibuat otomatis oleh sistem
    </div>

</body>
</html>