<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tanda Daftar SIPENSI</title>

    @php
        // Helper convert image path ke base64 (DomPDF-safe)
        $getImageBase64 = function($paths) {
            foreach ($paths as $path) {
                $path = str_replace('\\', '/', $path);
                if (file_exists($path) && is_readable($path)) {
                    try {
                        $content = @file_get_contents($path);
                        if ($content !== false && strlen($content) > 0) {
                            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            $mime = in_array($ext, ['jpg','jpeg']) ? 'jpeg' : 'png';
                            return 'data:image/' . $mime . ';base64,' . base64_encode($content);
                        }
                    } catch (\Exception $e) {}
                }
            }
            return '';
        };

        // Background image
        $bgPaths = [
            public_path('img/sertifikat/bg1.jpg'),
            public_path('assets/bg/bg-sertifikat.png'),
        ];
        $bgBase64 = $getImageBase64($bgPaths);

        // TTE image
        $ttePaths = [
            public_path('img/sertifikat/image_TTE.png'),
            public_path('assets/tte/ttd.png'),
        ];
        $tteBase64 = $getImageBase64($ttePaths);

        // TTD PENGIRIM (gambar kiri)
        $ttdPengirimPaths = [
            public_path('img/sertifikat/ttd_pengirim.png'),
        ];
        $ttdPengirimBase64 = $getImageBase64($ttdPengirimPaths);

        // QR (aman, kalau DNS2D gak ada ya kosong)
        $qrCodeBase64 = '';
        try {
            if (class_exists('DNS2D')) {
                $qrCodeUrl = isset($inkubator) ? route('inkubators.detail', $inkubator->id) : null;
                if ($qrCodeUrl) $qrCodeBase64 = DNS2D::getBarcodePNG($qrCodeUrl, 'QRCODE');
            }
        } catch (\Exception $e) {
            $qrCodeBase64 = '';
        }

        // Support 2 style data: pakai $inkubator atau pakai variable terpisah
        $no_tanda_daftar = $no_tanda_daftar ?? ($inkubator->no_tanda_daftar ?? '-');
        $nama_inkubator  = $nama_inkubator  ?? ($inkubator->nama_inkubator  ?? '-');
        $alamat          = $alamat          ?? ($inkubator->alamat_kantor   ?? '-');
        $tanggal         = $tanggal         ?? now();

        // Text di samping TTE
        $ttd_pengirim = $ttd_pengirim ?? ($inkubator->ttd_pengirim ?? '');
    @endphp

    <style>
        @page { size: A4 landscape; margin: 0; }
        html, body { margin: 0; padding: 0; }

        .page{
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }

        .bg{
            position:absolute; inset:0;
            z-index:1;
        }
        .bg img{ width:100%; height:100%; object-fit:cover; }

        /* AREA KOTAK PUTIH (canvas) */
        .content-box{
            position:absolute;
            z-index:10;

            /* Kunci area kotak putih */
            left: 14mm;
            top: 33mm;
            width: 269mm;
            height: 162mm;

            box-sizing:border-box;
        }

        /* QR di dalam kotak putih */
        .qr{
            position:absolute;
            z-index:20;
            top: 12mm;
            left: 12mm;
        }
        .qr img{ width: 30mm; height:auto; }

        /* BLOK TEKS UTAMA */
        .main-text{
            position:absolute;
            z-index:20;
            top: 18mm;           /* turun sedikit biar balance */
            left: 0;
            width: 100%;
            text-align:center;
        }

        /* FONT */
        .title{
            font-family: "Times New Roman", serif;
            font-size: 34pt;
            font-weight: 700;
            letter-spacing: 2px;
            margin: 0;
        }
        .subtitle{
            font-family: Arial, sans-serif;
            font-size: 14pt;
            font-weight: 700;
            margin-top: 6pt;
        }
        .no{
            font-family: Arial, sans-serif;
            font-size: 12pt;
            margin-top: 12pt;
        }
        .given{
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin-top: 14pt;
            letter-spacing: 1px;
        }
        .divider{
            width: 72%;
            height: 1px;
            background:#111;
            margin: 10pt auto;
        }
        .name{
            font-family: "Times New Roman", serif;
            font-size: 22pt;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 4pt;
        }
        .addr{
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.35;
            margin-top: 8pt;
            padding: 0 16%;
        }
        .date{
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin-top: 10pt;
        }

        /* TTE: CENTER BOTTOM + text di samping (POSISI TIDAK DIUBAH) */
        .tte-wrap{
            position:absolute;
            z-index:999;
            bottom: 55mm;

            /* center dompdf-safe */
            left: 50%;
            width: 190mm;              /* lebar blok (gambar + teks) */
            margin-left: -70mm;         /* biarin sesuai punyamu (tidak diubah) */

            display: table;
            table-layout: fixed;
        }

        /* 3 kolom: ttd_pengirim (kiri) + TTE (tengah) + text (kanan) */
        .ttd-left, .tte-img, .tte-text{
            display: table-cell;
            vertical-align: middle;
        }

        .ttd-left{
            width: 40mm;     /* cukup untuk ttd_pengirim.png */
        }
        .ttd-left img{
            width: 40mm;
            height: auto;
            margin-left: 15mm;  /* ke kiri */
        }

        .tte-img{
            width: 70mm;     /* biarin sesuai punyamu */
        }
        .tte-img img{
            width: 70mm;     /* biarin sesuai punyamu */
            height: auto;
        }

        .tte-text{
            padding-left: 8mm;
            font-family: Arial, sans-serif;
            font-size: 10pt;
            font-weight: 600;
            color: #000;
            line-height: 1.25;
        }
    </style>
</head>

<body>
<div class="page">

    {{-- BACKGROUND --}}
    <div class="bg">
        @if($bgBase64)
            <img src="{{ $bgBase64 }}" alt="Background">
        @else
            <div style="width:100%;height:100%;background:#1a3a5f;"></div>
        @endif
    </div>

    {{-- CONTENT INSIDE WHITE BOX --}}
    <div class="content-box">

        {{-- QR --}}
        @if($qrCodeBase64)
            <div class="qr">
                <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code">
            </div>
        @endif

        {{-- MAIN TEXT --}}
        <div class="main-text">
            <div class="title">TANDA DAFTAR</div>

            <div class="subtitle">SISTEM PENDAFTARAN INFORMASI DAN EVALUASI INKUBASI</div>

            <div class="no">No : {{ $no_tanda_daftar }}</div>

            <div class="given">DIBERIKAN KEPADA</div>

            <div class="divider"></div>

            <div class="name">{{ $nama_inkubator }}</div>

            <div class="divider"></div>

            <div class="addr">{!! nl2br(e($alamat)) !!}</div>

            <div class="date">
                Jakarta, {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
            </div>
        </div>

        {{-- TTD PENGIRIM + TTE + TEXT (posisi tetap) --}}
        @if($tteBase64)
            <div class="tte-wrap">

                {{-- kiri: ttd_pengirim.png --}}
                @if($ttdPengirimBase64)
                    <div class="ttd-left">
                        <img src="{{ $ttdPengirimBase64 }}" alt="TTD Pengirim">
                    </div>
                @else
                    <div class="ttd-left"></div>
                @endif

                {{-- tengah: TTE --}}
                <div class="tte-img">
                    <img src="{{ $tteBase64 }}" alt="TTE">
                </div>

                {{-- kanan: text --}}
                <div class="tte-text">
                    {{ $ttd_pengirim }}
                </div>

            </div>
        @endif

    </div>
</div>
</body>
</html>
