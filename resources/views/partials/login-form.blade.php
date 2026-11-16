{{--
    Form login yang dipakai bersama oleh admin, dosen, dan mahasiswa.
    Variabel: $icon, $title, $subtitle, $action, $emailLabel, $emailPlaceholder, $button
--}}
<div class="auth-head">
    <span class="auth-icon" aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
    <h1 class="auth-title">{{ $title }}</h1>
    <p class="auth-sub">{{ $subtitle }}</p>
</div>

@if($errors->any())
    <div class="auth-alert" role="alert">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

<form action="{{ $action }}" method="POST" class="auth-form">
    @csrf

    <div class="field">
        <label for="email">{{ $emailLabel }}</label>
        <div class="field-box {{ $errors->has('email') ? 'has-error' : '' }}">
            <i class="bi bi-envelope lead-ico" aria-hidden="true"></i>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   placeholder="{{ $emailPlaceholder }}"
                   autocomplete="username"
                   inputmode="email"
                   autofocus
                   required>
        </div>
    </div>

    <div class="field">
        <label for="password">Password</label>
        <div class="field-box {{ $errors->has('password') ? 'has-error' : '' }}">
            <i class="bi bi-lock lead-ico" aria-hidden="true"></i>
            <input type="password"
                   id="password"
                   name="password"
                   placeholder="Masukkan password"
                   autocomplete="current-password"
                   required>
            <button type="button" class="toggle-pass" data-toggle-password="password" aria-label="Tampilkan password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn-auth">
        <span class="spinner-border spinner-border-sm spin" aria-hidden="true"></span>
        <span>{{ $button }}</span>
    </button>
</form>

<a href="{{ route('login') }}" class="auth-back">
    <i class="bi bi-arrow-left"></i> Kembali ke pilihan login
</a>