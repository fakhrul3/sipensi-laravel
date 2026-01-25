<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sertifikat SIPENSI - {{ $inkubator->nama_inkubator ?? '' }}</title>
    @php
        // Helper untuk convert path ke base64 dengan error handling
        $getImageBase64 = function($paths) {
            foreach ($paths as $path) {
                // Normalisasi path untuk Windows (backslash ke forward slash)
                $path = str_replace('\\', '/', $path);
                
                if (file_exists($path) && is_readable($path)) {
                    try {
                        $content = @file_get_contents($path);
                        if ($content !== false && strlen($content) > 0) {
                            // Validasi bahwa ini benar-benar file gambar
                            $imageInfo = @getimagesize($path);
                            if ($imageInfo === false) {
                                continue; // Bukan file gambar yang valid
                            }
                            
                            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            $mimeType = $extension === 'png' ? 'png' : ($extension === 'jpg' || $extension === 'jpeg' ? 'jpeg' : 'png');
                            return 'data:image/' . $mimeType . ';base64,' . base64_encode($content);
                        }
                    } catch (\Exception $e) {
                        // Skip file yang error, coba path berikutnya
                        \Log::warning('Error reading image file: ' . $path . ' - ' . $e->getMessage());
                        continue;
                    }
                }
            }
            return '';
        };
        
        // Cek path background - prioritas bg1.jpg
        $bgPaths = [
            public_path('img/sertifikat/bg1.jpg'),
            public_path('img/sertifikat/bg1.png'),
            public_path('assets/images/bg1.jpg'),
            public_path('assets/images/bg1.png'),
        ];
        $bgBase64 = $getImageBase64($bgPaths);
        
        // Cek path TTE - prioritas images_TTE.png
        $ttePaths = [
            public_path('img/sertifikat/images_TTE.png'),
            public_path('img/sertifikat/image_TTE.png'),
            public_path('assets/images/images_TTE.png'),
            public_path('assets/images/image_TTE.png'),
        ];
        $tteBase64 = $getImageBase64($ttePaths);
    @endphp
    <style>
        @page {
            margin: 0.6in;
            size: A4 landscape;
            orientation: landscape;
        }

        .text-sertifikat {
            font-family: 'Roboto', serif;
            font-weight: 700;
            font-style: normal;
        }

        .text-lainnya {
            font-family: Tahoma, Verdana, Segoe, sans-serif;
        }

        body:before {
            display: block;
            position: fixed;
            top: -0.6in;
            right: -0.6in;
            bottom: -0.6in;
            left: -0.6in;
            @if($bgBase64)
            background-image: url('{{ $bgBase64 }}');
            @else
            background-color: #ffffff;
            @endif
            background-size: 100% 100%;
            background-repeat: no-repeat;
            content: "";
            z-index: -10;
        }

        .ImageTTE {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <div style="margin-top:135px; text-align: center;">
        <label class="text-sertifikat"
            style="font-size: 48px; color: #000; letter-spacing: 2px; font-family: 'Roboto', serif; font-weight: 700">TANDA
            DAFTAR</label>
    </div>
    <div style="margin-top:10px; text-align: center; padding-left: 10%; padding-right: 10%;">
        <div style="text-align: center; border: 0px blue solid; width: 100%;">
            <label class="text-lainnya" style="font-size: 20px; text-wrap:initial; color: #000; line-height: 1.2">SISTEM
                PENDAFTARAN INFORMASI DAN EVALUASI INKUBASI</label>
        </div>
    </div>
    <div style="margin-top:20px; text-align: center;">
        <label class="text-lainnya"
            style="font-size: 24px; color: #000; font-weight: bold; letter-spacing: 2px;">{{ 'No : ' . ($inkubator->no_tanda_daftar ?? '-') }}</label>
    </div>
    <div style="margin-top:15px; text-align: center; padding-left: 28%; padding-right: 28%;">
        <div style="text-align: center; border: 0px blue solid; width: 100%;">
            <label class="text-lainnya"
                style="font-size: 20px; color: #000; line-height: 1.2; letter-spacing: 2px;">DIBERIKAN KEPADA</label>
        </div>
    </div>
    <div style="margin-top:0px; text-align: center; border: 0px red solid; position: relative;">
        <div style="position: absolute; top: 3%; left: 50%; transform: translate(-50%, -50%); width: 50%;">
            <hr style="border: 1.5px solid #000;">
        </div>
    </div>
    <div style="margin-top:20px; text-align: center; height: 100px; border: 0px red solid; position: relative;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%;">
            <label
                style="font-size: 30px; color: #000; font-weight: bold; line-height: 1.2;">{{ $inkubator->nama_inkubator ?? '-' }}</label>
        </div>
    </div>
    <div style="margin-top:0px; text-align: center; border: 0px red solid; position: relative;">
        <div style="position: absolute; top: 0%; left: 50%; transform: translate(-50%, -50%); width: 50%;">
            <hr style="border: 1.5px solid #000;">
        </div>
    </div>
    <div style="margin-top:15px; height: 70px; border: 0px red solid; padding-left: 5%; padding-right: 5%;">
        <div style="text-align: center; border: 0px blue solid; width: 100%;">
            <label class="text-lainnya"
                style="font-size: 16px; color: #000; line-height: 1.2">{!! nl2br(e($inkubator->alamat_kantor ?? '-')) !!}</label>
            <br><br><label class="text-lainnya"
                style="font-size: 16px; color: #000;">{{ 'Jakarta ' . date('d.m.Y') }}</label>
        </div>
    </div>
    <div style="margin-top:15px; height: 70px; border: 0px red solid; padding-left: 20%; padding-right: 10%;">
        <div style="text-align: left; border: 0px blue solid; width: 100%;">
            <div style="display: flex; align-items: center; gap: 15px;">
                @if($tteBase64)
                    <img src="{{ $tteBase64 }}" alt="Tanda Tangan" class="ImageTTE"/>
                @else
                    <div class="ImageTTE" style="width: 80px; height: 80px; background: #f0f0f0; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #666;">TTE</div>
                @endif
                <div style="font-size: 12px; color: #000;">
                    <div style="margin-bottom: 5px;">Ditandatangani secara elektronik oleh:</div>
                    <div style="margin-bottom: 5px;">Asisten Deputi Inkubasi dan Digitalisasi Wirausaha,</div>
                    <div style="font-weight: bold; margin-top: 8px;">Irwansyah Putra, S.STP., M.Si.</div>
                    <div style="margin-top: 3px;">NIP 19800814 2000031001</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
