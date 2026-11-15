<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Dosen - Absensi Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6fb;
        }
        .main-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-4">

<div class="container">
    <div class="mx-auto" style="max-width: 430px; width: 100%;">
        <div class="card main-card p-4 p-md-5">

            <div class="text-center mb-4">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:65px; height:65px; background:#e0f5f1; border-radius:18px;">
                    <span style="font-size:30px; color:#0f766e;">
                        <i class="bi bi-person-workspace"></i>
                    </span>
                </div>
                <h3 class="fw-bold mb-1">Login Dosen</h3>
                <p class="text-secondary mb-0">Masuk ke portal pengajaran dosen</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger py-2">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('dosen.login.process') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Dosen</label>
                    <input type="email"
                           name="email"
                           class="form-control form-control-lg"
                           placeholder="dosen@kampus.ac.id"
                           value="{{ old('email') }}"
                           required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password"
                           name="password"
                           class="form-control form-control-lg"
                           placeholder="Masukkan password"
                           required>
                </div>

                <button type="submit"
                        class="btn btn-lg w-100 text-white fw-semibold"
                        style="background:#0f766e;">
                    Masuk sebagai Dosen
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-decoration-none" style="color:#0f766e;">
                    &larr; Kembali ke pilihan login
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>