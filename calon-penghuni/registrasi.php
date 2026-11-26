<?php
$base = '../';
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fe.php';

// Sudah masuk: langsung ke area sesuai role (tidak perlu daftar ulang).
if (!empty($_SESSION['user_id'])) {
    redirect($_SESSION['role'] === 'pengelola' ? '../admin/index.php' : '../penghuni/index.php');
}

$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

fe_awal_auth('Buat Akun', ['auth']);
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

      <div class="informasi">
        <span class="icon">ℹ️</span>
        <span>Buat akun untuk melanjutkan reservasi kamar yang anda pilih. NIK dan foto KTP baru diminta saat reservasi.</span>
      </div>

      <div class="auth-header">
        <h1 class="judul-box">Buat Akun</h1>
        <p class="deskripsi">Lengkapi data diri kamu untuk mulai membuat akun.</p>
      </div>

      <form class="auth-form" action="../auth/proses_register.php" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
          <label for="nama" class="form-label">Nama Lengkap</label>
          <input
            type="text"
            id="nama"
            name="nama_lengkap"
            class="form-input"
            maxlength="100"
            placeholder="Nama lengkap sesuai KTP"
            value="<?= e($old['nama_lengkap'] ?? '') ?>"
            required
          >
        </div>

        <div class="form-group">
          <label for="nohp" class="form-label">Nomor HP / WhatsApp</label>
          <input
            type="tel"
            id="nohp"
            name="no_hp"
            class="form-input"
            maxlength="15"
            placeholder="08xxxxxxxxxx"
            value="<?= e($old['no_hp'] ?? '') ?>"
            required
          >
        </div>

        <div class="form-group">
          <label for="username" class="form-label">Username</label>
          <input
            type="text"
            id="username"
            name="username"
            class="form-input"
            maxlength="50"
            autocomplete="username"
            placeholder="Huruf, angka, titik, atau garis bawah"
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
              minlength="8"
              autocomplete="new-password"
              placeholder="Minimal 8 karakter"
              required
            >
          </div>
        </div>

        <div class="form-group">
          <label for="konfirmasi" class="form-label">Konfirmasi Kata Sandi</label>
          <div class="input-password-wrapper">
            <input
              type="password"
              id="konfirmasi"
              name="konfirmasi"
              class="form-input"
              autocomplete="new-password"
              placeholder="Ulangi kata sandi kamu"
              required
            >
          </div>
        </div>

        <div class="form-checkbox">
          <input type="checkbox" id="persetujuan" name="persetujuan" required>
          <label for="persetujuan">
            Saya menyetujui <a href="aturan.php" target="_blank" rel="noopener">aturan kos</a>
          </label>
        </div>

        <button type="submit" class="btn btn-primary btn-full">Daftar</button>
      </form>

      <div class="auth-footer-card">
        Sudah punya akun? <a href="login.php" class="link-auth">Masuk</a>
      </div>
    </div>

    <a href="daftar-kamar.php" class="back-link back-link-center">← Kembali ke Daftar Kamar</a>
  </div>

</body>
</html>