<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pengajuan - {{ $pengajuan->kode_pengajuan }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #333333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #f25920;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header table {
            width: 100%;
        }
        .header h2 {
            margin: 0;
            color: #f25920;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0 0 0;
            color: #666666;
            font-size: 9pt;
        }
        .badge-status {
            display: inline-block;
            padding: 4px 10px;
            font-size: 9pt;
            font-weight: bold;
            color: #ffffff;
            background-color: #28a745;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
            margin-top: 15px;
            margin-bottom: 8px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #d1d5db;
            padding: 5px 8px;
            font-size: 9.5pt;
            text-align: left;
        }
        table.data-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
        }
        .photo-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .photo-card {
            width: 50%;
            padding: 5px;
            vertical-align: top;
            box-sizing: border-box;
        }
        .photo-box {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 6px;
            background-color: #f9fafb;
            text-align: center;
        }
        .photo-box img {
            max-width: 100%;
            max-height: 150px;
            object-fit: contain;
            border-radius: 4px;
        }
        .photo-title {
            font-weight: bold;
            font-size: 9.5pt;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .photo-sub {
            font-size: 8.5pt;
            color: #4b5563;
            margin-bottom: 3px;
            font-weight: bold;
        }
        .no-photo {
            height: 90px;
            line-height: 90px;
            color: #9ca3af;
            font-style: italic;
            background-color: #f3f4f6;
            border-radius: 4px;
            font-size: 8.5pt;
        }
        .footer {
            margin-top: 20px;
            font-size: 8pt;
            color: #9ca3af;
            text-align: right;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <h2>Laporan Pengajuan Peminjaman</h2>
                    <p>Kode Pengajuan: <strong>{{ $pengajuan->kode_pengajuan }}</strong></p>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <span class="badge-status">{{ $pengajuan->progress }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Detail Pengajuan</div>
    <table class="data-table">
        <tr>
            <th width="35%">Kode Pengajuan</th>
            <td>{{ $pengajuan->kode_pengajuan }}</td>
        </tr>
        <tr>
            <th>Jenis / Kategori Aset</th>
            <td>{{ optional($pengajuan->aset)->kategori ?? $pengajuan->jenis ?? '-' }}</td>
        </tr>
        <tr>
            <th>Kode Aset</th>
            <td>{{ optional($pengajuan->aset)->kode_aset ?? $pengajuan->aset_kode ?? '-' }}</td>
        </tr>
        <tr>
            <th>Perusahaan Tamu</th>
            <td>{{ $pengajuan->perusahaan_tamu }}</td>
        </tr>
        <tr>
            <th>Perusahaan Pengutus</th>
            <td>{{ $pengajuan->perusahaan_pengutus }}</td>
        </tr>
        <tr>
            <th>Jumlah Tamu</th>
            <td>{{ $pengajuan->jumlah }} Orang</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td>{{ $pengajuan->kategori }}</td>
        </tr>
        <tr>
            <th>Keperluan</th>
            <td>{{ $pengajuan->keperluan }}</td>
        </tr>
        <tr>
            <th>Lama Kerja</th>
            <td>{{ $pengajuan->lama_berkerja }}</td>
        </tr>
        <tr>
            <th>Identitas & Masa Berlaku</th>
            <td>{{ $pengajuan->identitas }} (Masa berlaku: {{ $pengajuan->masa_berlaku }})</td>
        </tr>
        <tr>
            <th>Pembuat</th>
            <td>{{ optional($pengajuan->creator)->nama ?? '-' }}</td>
        </tr>
        <tr>
            <th>Tanggal Dibuat</th>
            <td>{{ $pengajuan->created_at->format('d-m-Y H:i') }}</td>
        </tr>
    </table>

    <div class="section-title">Detail Tamu</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Tamu</th>
                <th>No Telepon</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengajuan->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->nama_tamu }}</td>
                    <td>{{ $detail->no_tlp }}</td>
                    <td>{{ $detail->email }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #9ca3af;">Tidak ada data tamu</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">Foto Dokumentasi</div>
    <table class="photo-grid">
        @foreach (array_chunk($fotoRows, 2) as $chunk)
            <tr>
                @foreach ($chunk as $row)
                    <td class="photo-card">
                        <div class="photo-box">
                            <div class="photo-title">{{ $row['label'] }}</div>
                            <table style="width: 100%; border: none;">
                                <tr>
                                    <td style="width: 50%; padding: 2px; border: none; text-align: center;">
                                        <div class="photo-sub">Pergi</div>
                                        @if ($row['pergi_base64'])
                                            <img src="{{ $row['pergi_base64'] }}" alt="{{ $row['label'] }} Pergi">
                                        @else
                                            <div class="no-photo">Tidak ada foto</div>
                                        @endif
                                    </td>
                                    <td style="width: 50%; padding: 2px; border: none; text-align: center;">
                                        <div class="photo-sub">Kembali</div>
                                        @if ($row['kembali_base64'])
                                            <img src="{{ $row['kembali_base64'] }}" alt="{{ $row['label'] }} Kembali">
                                        @else
                                            <div class="no-photo">Tidak ada foto</div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                @endforeach
                @if (count($chunk) < 2)
                    <td class="photo-card"></td>
                @endif
            </tr>
        @endforeach
    </table>

    <div class="section-title" style="margin-top: 20px;">Riwayat Pengajuan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="18%">Aksi</th>
                <th>Keterangan</th>
                <th width="20%">Oleh</th>
                <th width="22%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengajuan->logs as $index => $log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ ucfirst($log->aksi) }}</td>
                    <td>{{ $log->keterangan ?? '-' }}</td>
                    <td>{{ optional($log->creator)->nama ?? '-' }}</td>
                    <td>{{ $log->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #9ca3af;">Belum ada riwayat log</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis pada {{ date('d-m-Y H:i:s') }}
    </div>

</body>
</html>
