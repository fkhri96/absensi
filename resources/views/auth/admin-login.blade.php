<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Absensi Kampus</title>
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
                     style="width:65px; height:65px; background:#e8eeff; border-radius:18px;">
                    <span style="font-size:30px; color:#3157d5;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </span>
                </div>
                <h3 class="fw-bold mb-1">Login Admin</h3>
                <p class="text-secondary mb-0">Masuk ke panel administrator</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger py-2">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.process') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Admin</label>
                    <input type="email"
                           name="email"
                           class="form-control form-control-lg"
                           placeholder="admin@kampus.ac.id"
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
                        style="background:#3157d5;">
                    Masuk sebagai Admin
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-decoration-none">
                    &larr; Kembali ke pilihan login
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>