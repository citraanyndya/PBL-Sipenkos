<?php
$base = '../';
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fe.php';

// Sudah masuk: langsung ke area sesuai role (tidak perlu login ulang).
if (!empty($_SESSION['user_id'])) {
    redirect($_SESSION['role'] === 'pengelola' ? '../admin/index.php' : '../penghuni/index.php');
}

$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

fe_awal_auth('Masuk', ['auth', 'login-tab']);
?>

  <div class="auth-container">
    <div class="card-box auth-card">
      <div class="auth-logo">
        <a href="beranda.php" class="logo">
          <span class="logo-judul">SIPENKOS</span>
          <span class="logo-divider">|</span>
          <span class="logo-pendukung">Kos Bu Nining</span>
        </a>
      </div>

      <?php fe_flash(); ?>

      <div class="login-tabs" role="tablist" aria-label="Jenis akun">
        <button
          type="button"
          role="tab"
          id="tab-penghuni"
          class="login-tab is-active"
          aria-selected="true"
          aria-controls="panel-penghuni"
        >
          Penghuni
        </button>
        <button
          type="button"
          role="tab"
          id="tab-pengelola"
          class="login-tab"
          aria-selected="false"
          aria-controls="panel-pengelola"
          tabindex="-1"
        >
          Pengelola
        </button>
      </div>

      <!-- Panel Login Penghuni -->
      <section id="panel-penghuni" role="tabpanel" aria-labelledby="tab-penghuni" class="login-panel">
        <div class="auth-header">
          <h1 class="judul-box">Masuk ke SIPENKOS</h1>
          <p class="deskripsi">Masukkan username dan kata sandi kamu untuk melanjutkan.</p>
        </div>

        <form class="auth-form" action="../auth/proses_login.php" method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="tipe" value="penghuni">

          <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input
              type="text"
              id="username"
              name="username"
              class="form-input"
              placeholder="Masukkan username"
              autocomplete="username"
              value="<?= e($old['username'] ?? '') ?>"
              required
            >
          </div>

          <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <div class="input-password-wrapper">
              <input
                type="password"
                id="password"
                name="password"
                class="form-input"
                placeholder="Masukkan kata sandi kamu"
                autocomplete="current-password"
                required
              >
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-full">Masuk</button>
        </form>

        <div class="auth-footer-card">
          Belum punya akun? <a href="registrasi.php" class="link-auth">Daftar sekarang</a>
        </div>
      </section>

      <!-- Panel Login Pengelola -->
      <section id="panel-pengelola" role="tabpanel" aria-labelledby="tab-pengelola" class="login-panel" hidden>
        <div class="auth-header">
          <h1 class="judul-box">Masuk Pengelola</h1>
          <p class="deskripsi">Khusus pengelola kos untuk mengelola reservasi, pembayaran, dan laporan.</p>
        </div>

        <form class="auth-form" action="../auth/proses_login.php" method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="tipe" value="pengelola">

          <div class="form-group">
            <label for="pengelola-username" class="form-label">Username</label>
            <input
              type="text"
              id="pengelola-username"
              name="username"
              class="form-input"
              placeholder="Masukkan username"
              autocomplete="username"
              required
            >
          </div>

          <div class="form-group">
            <label for="pengelola-sandi" class="form-label">Kata Sandi</label>
            <div class="input-password-wrapper">
              <input
                type="password"
                id="pengelola-sandi"
                name="password"
                class="form-input"
                placeholder="Masukkan kata sandi"
                autocomplete="current-password"
                required
              >
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-full">Masuk</button>
        </form>
      </section>
    </div>

    <a href="daftar-kamar.php" class="back-link back-link-center">← Kembali ke Daftar Kamar</a>
  </div>

  <script src="../assets/js/calon-penghuni/login-tab.js"></script>
</body>
</html>