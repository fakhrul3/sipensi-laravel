<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil | SIPENSI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, rgb(64, 88, 197) 0%, rgb(17, 0, 94) 100%);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .header-gradient {
            background: linear-gradient(to right, #2563eb, rgb(49, 38, 211));
            height: 10px;
            width: 100%;
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: #f0f7ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 50px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(37, 99, 235, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
        }

        .btn-primary {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #1d4ed8, #1e40af);
            transform: scale(1.02);
            box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
        }

        .alert-custom {
            background-color: #f8fafc;
            border-left: 5px solid #2563eb;
            border-radius: 12px;
            color: #475569;
        }

        .username-badge {
            background-color: #e0e7ff;
            color: #4338ca;
            padding: 2px 10px;
            border-radius: 6px;
            font-weight: 600;
        }

        /* Animasi masuk untuk alert */
        .fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg">
                    <div class="header-gradient"></div>
                    <div class="card-body p-5 text-center">
                        <div class="icon-wrapper">
                            📩
                        </div>
                        
                        <h2 class="fw-bold text-dark mb-2">Cek Email Anda!</h2>
                        <p class="text-muted mb-4">
                            Halo <span class="username-badge">{{ $username }}</span>, pendaftaran Anda berhasil diproses.
                        </p>
                        
                        {{-- BLOK NOTIFIKASI --}}
                        <div class="mb-4 fade-in-up">
                            @if(session('success'))
                                <div class="alert alert-success border-0 small py-2 shadow-sm text-start">
                                    ✅ {{ session('success') }}
                                </div>
                            @endif

                            @if($errors->has('error'))
                                <div class="alert alert-danger border-0 small py-2 shadow-sm text-start" id="countdown-wrapper">
                                    ⚠️ <strong>Maaf:</strong> <span id="error-message">{{ $errors->first('error') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="alert alert-custom text-start mb-4 shadow-sm">
                            <small class="fw-bold d-block mb-1 text-primary text-uppercase">Instruksi:</small>
                            Kami telah mengirimkan link verifikasi ke email Anda. Silakan klik link tersebut untuk mengaktifkan akun Anda agar dapat ditinjau oleh Admin.
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary text-white">
                                Ke Halaman Login
                            </a>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top">
                            <p class="small text-secondary mb-0">
                                Tidak menerima email? Periksa folder <b>Spam</b> atau 
                                <a href="{{ route('resend.mail', $username) }}" class="text-decoration-none fw-bold text-primary">Kirim Ulang</a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <p class="text-center text-white mt-4 small opacity-75">
                    &copy; {{ date('Y') }} SIPENSI - Sistem Informasi Inkubator Bisnis
                </p>
            </div>
        </div>
    </div>

    {{-- SCRIPT COUNTDOWN OTOMATIS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const errorSpan = document.getElementById('error-message');
            const wrapper = document.getElementById('countdown-wrapper');
            
            if (errorSpan) {
                // Mencari angka detik dalam teks error (misal: "300")
                let text = errorSpan.innerText;
                let secondsMatch = text.match(/\d+/);
                
                if (secondsMatch) {
                    let seconds = parseInt(secondsMatch[0]);
                    
                    const timer = setInterval(function () {
                        seconds--;
                        
                        if (seconds <= 0) {
                            clearInterval(timer);
                            // Ubah tampilan jadi info setelah waktu habis
                            errorSpan.innerHTML = "Anda sudah bisa mengirim ulang email sekarang. Silakan klik link <b>Kirim Ulang</b>.";
                            if(wrapper) {
                                wrapper.classList.replace('alert-danger', 'alert-info');
                            }
                        } else {
                            // Update teks angka detik secara real-time
                            errorSpan.innerText = text.replace(/\d+/, seconds);
                        }
                    }, 1000);
                }
            }
        });
    </script>
</body>
</html>