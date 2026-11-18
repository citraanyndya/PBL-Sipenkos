<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIPENKOS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/calon-penghuni/style.css">
    <link rel="stylesheet" href="../assets/css/calon-penghuni/auth.css">
    <link rel="stylesheet" href="../assets/css/calon-penghuni/login-tab.css">
    <script src="../assets/js/common/sesi.js" data-izin="tamu"></script>
</head>
<body>
  <div class="auth-container">
    <div class="card-box auth-card">
      <div class="auth-logo">
        <a href="beranda.php" class="logo">
          <span class="logo-judul">SIPENKOS</span>
          <span class="logo-divider">|</span>
          <span class="logo-pendukung">Kos Bu Nining</span>
        </a>
      </div>

      <div class="login-tabs" role="tablist" aria-label="Jenis akun">
        <button type="button" role="tab" id="tab-penghuni" class="login-tab is-active" aria-selected="true" aria-controls="panel-penghuni">Penghuni</button>
        <button type="button" role="tab" id="tab-pengelola" class="login-tab" aria-selected="false" aria-controls="panel-pengelola" tabindex="-1">Pengelola</button>
      </div>

      <!-- ===== Panel Penghuni (ditangani auth.js: form .auth-form pertama) ===== -->
      <section id="panel-penghuni" role="tabpanel" aria-labelledby="tab-penghuni" class="login-panel">
        <div class="auth-header">
          <h1 class="judul-box">Masuk ke SIPENKOS</h1>
          <p class="deskripsi">Masukkan email dan kata sandi kamu untuk melanjutkan.</p>
        </div>

        <form class="auth-form" action="#" method="POST">
          <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-input" placeholder="nama@email.com" required>
          </div>

          <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <div class="input-password-wrapper">
              <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan kata sandi kamu" required>
            </div>
          </div>

          <div class="auth-options">
            <div class="form-checkbox">
              <input type="checkbox" id="ingat_saya" name="ingat_saya">
              <label for="ingat_saya">Ingat saya</label>
            </div>
            <a href="#" class="link-auth-sm">Lupa kata sandi?</a>
          </div>
          <button type="submit" class="btn btn-primary btn-full">Masuk</button>
        </form>

        <div class="auth-footer-card">
          Belum punya akun? <a href="registrasi.php" class="link-auth">Daftar sekarang</a>
        </div>
      </section>

      <!-- ===== Panel Pengelola (ditangani login-tab.js) ===== -->
      <section id="panel-pengelola" role="tabpanel" aria-labelledby="tab-pengelola" class="login-panel" hidden>
        <div class="auth-header">
          <h1 class="judul-box">Masuk Pengelola</h1>
          <p class="deskripsi">Khusus pengelola kos untuk mengelola reservasi, pembayaran, dan laporan.</p>
        </div>

        <form class="auth-form-pengelola" id="form-pengelola" novalidate>
          <div class="form-group">
            <label for="pengelola-username" class="form-label">Username</label>
            <input type="text" id="pengelola-username" class="form-input" placeholder="Masukkan username" autocomplete="username">
          </div>

          <div class="form-group">
            <label for="pengelola-sandi" class="form-label">Kata Sandi</label>
            <div class="input-password-wrapper">
              <input type="password" id="pengelola-sandi" class="form-input" placeholder="Masukkan kata sandi" autocomplete="current-password">
            </div>
          </div>

          <p class="login-galat" id="pengelola-galat" role="alert" hidden></p>
          <button type="submit" class="btn btn-primary btn-full">Masuk</button>
        </form>

        <!-- DEMO: hapus saat login memakai backend (POST /api/auth/admin/login) -->
        <div class="informasi">
          <span class="icon">ℹ️</span>
          <span>Contoh · username <strong>admin</strong>, kata sandi <strong>admin12345</strong></span>
        </div>
      </section>
    </div>
    <a href="daftar-kamar.php" class="back-link back-link-center">← Kembali ke Daftar Kamar</a>
  </div>
  <script src="../assets/js/calon-penghuni/auth.js"></script>
  <script src="../assets/js/calon-penghuni/login-tab.js"></script>
</body>
</html>
