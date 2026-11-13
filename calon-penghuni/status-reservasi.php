<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Reservasi &amp; Pembayaran - SIPENKOS</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../assets/css/calon-penghuni/style.css">
  <link rel="stylesheet" href="../assets/css/calon-penghuni/status-reservasi.css">
</head>
<body>
  <header class="navbar">
    <div class="container navbar-container">
      <a href="beranda.php" class="logo">
        <span class="logo-judul">SIPENKOS</span>
        <span class="logo-divider">|</span>
        <span class="logo-pendukung">Kos Bu Nining</span>
      </a>

      <div class="nav-actions-wrapper">
        <div class="nav-actions" id="guestActions">
          <a href="registrasi.php" class="btn btn-outline">Daftar</a>
          <a href="login.php" class="btn btn-primary">Masuk</a>
        </div>

        <div class="user-profile" id="userProfile" style="display: none;">
          <button type="button" class="btn-icon-notif" title="Notifikasi" aria-label="Notifikasi">
            🔔<span class="badge-dot"></span>
          </button>
          <div class="user-info">
            <div class="user-avatar" id="headerAvatar"></div>
            <span class="user-name" id="headerName"></span>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main class="section-halaman">
    <div class="container">
      <a href="pembayaran.php" class="back-link">
        ← Kembali ke Pembayaran
      </a>

      <div class="section-header">
        <h1 class="judul-section">Status Reservasi &amp; Pembayaran</h1>
        <p class="deskripsi">Pantau proses reservasi dan pembayaran kamu</p>
      </div>

      <div class="alur-card" aria-label="Tahapan reservasi">
        <div class="alur-sewa completed">
          <span class="nomor">✓</span>
          <span class="alur-label">Formulir Reservasi</span>
        </div>
        <span class="step-arrow">→</span>
        <div class="alur-sewa completed">
          <span class="nomor">✓</span>
          <span class="alur-label">Pembayaran</span>
        </div>
        <span class="step-arrow">→</span>
        <div class="alur-sewa active" id="langkahVerifikasi">
          <span class="nomor" id="langkahNomor">3</span>
          <span class="alur-label" id="langkahLabel">Menunggu Verifikasi</span>
        </div>
      </div>

      <section class="status-banner status-banner--menunggu" id="statusBanner" aria-live="polite">
        <span class="status-ikon" id="statusIkon" aria-hidden="true">◷</span>
        <div class="status-isi">
          <span class="status-pill" id="statusPill">Menunggu Verifikasi</span>
          <p id="statusPesan"></p>
        </div>
        <a href="#" class="btn btn-outline" id="statusAksi"></a>
      </section>

      <!-- SIMULASI VERIFIKASI (KHUSUS DEVELOPMENT) -->
<section class="simulasi-verifikasi" id="simulasiVerifikasi" hidden>
    <div class="simulasi-header">
        <h3>Simulasi Verifikasi Pemilik Kos</h3>
        <p>
            Pilih hasil pemeriksaan untuk menguji alur reservasi.
            Bagian ini hanya digunakan selama pengembangan.
        </p>
    </div>

    <div class="simulasi-actions">
        <button type="button" id="btnTerimaReservasi" class="btn-simulasi btn-terima">
            ✓ Terima Reservasi
        </button>

        <button type="button" id="btnTolakReservasi" class="btn-simulasi btn-tolak">
            ✕ Tolak Reservasi
        </button>
    </div>
</section>

      <div class="grid-dua-kolom grid-dua-kolom--sama">
        <section class="card-box">
          <h2 class="judul-box">Ringkasan Reservasi</h2>

          <div class="foto-kamar">
            <img id="fotoKamar" src="" alt="Foto kamar">
          </div>

          <div class="rincian-list" id="ringkasanList"></div>

          <div class="total-box">
            <span id="nominalLabel">Dibayar</span>
            <strong id="nominalDibayar">Rp 0</strong>
          </div>
        </section>

        <section class="card-box">
          <h2 class="judul-box">Alur Reservasi</h2>
          <ol class="timeline" id="timelineList"></ol>
        </section>
      </div>
    </div>
  </main>

  <dialog class="dialog-bukti" id="dialogBukti">
    <h2 class="judul-box">Bukti Pembayaran</h2>
    <img class="dialog-bukti-gambar" id="buktiGambar" alt="Bukti pembayaran" hidden>
    <p class="pesan-memuat" id="buktiPesan"></p>
    <button type="button" class="btn btn-outline btn-full" id="tutupBukti">Tutup</button>
  </dialog>

  <footer class="footer">
    <div class="logo-judul text-white">SIPENKOS</div>
    <p class="footer-tagline">🛡️ Hunian terpercaya &amp; dikelola langsung oleh pemilik</p>
    <p class="footer-location">Kos Bu Nining · Malang, Jawa Timur</p>
    <div class="kontak">
      <span>📍 Jl. Pisang Agung III No. 29</span>
      <span>💬 WhatsApp 0854-4421-1076</span>
      <span>⏰ Jam bertamu 07.00 - 22.00 WIB</span>
    </div>
    <div class="copyright">© 2026 SIPENKOS - Kos Bu Nining Malang</div>
  </footer>

  <script src="../assets/js/calon-penghuni/main.js"></script>
  <script src="../assets/js/calon-penghuni/status-reservasi.js"></script>
</body>
</html>