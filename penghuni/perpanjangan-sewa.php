<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpanjangan Sewa - SIPENKOS</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../assets/css/penghuni/style.css">
  <link rel="stylesheet" href="../assets/css/penghuni/perpanjangan-sewa.css">
</head>
<body>
  <div class="perpanjangan-layout">
    <aside class="sidebar" id="sidebar">
      <nav class="sidebar-nav" aria-label="Navigasi utama">
        <a class="nav-link" href="dashboard.php"><span class="nav-icon">▦</span>Dashboard</a>
        <a class="nav-link" href="tagihan.php"><span class="nav-icon">▤</span>Tagihan</a>
        <a class="nav-link" href="riwayat-pembayaran.php"><span class="nav-icon">◴</span>Riwayat Pembayaran</a>
        <a class="nav-link active" href="perpanjangan-sewa.php" aria-current="page"><span class="nav-icon">▣</span>Perpanjangan Sewa</a>
        <a class="nav-link" href="laporan-kerusakan.php"><span class="nav-icon">⚒</span>Laporan Kerusakan</a>
        <a class="nav-link" href="aturan.php"><span class="nav-icon">⚑</span>Kebijakan &amp; Aturan Kos</a>
      </nav>

      <div class="sidebar-bottom">
        <button class="logout-button" id="logoutButton" type="button"><span class="nav-icon">⇥</span>Keluar</button>
      </div>
    </aside>

    <main class="main-area">
      <header class="navbar">
        <div class="container navbar-container">
          <a href="dashboard.php" class="logo">
            <span class="logo-judul">SIPENKOS</span>
            <span class="logo-divider">|</span>
            <span class="logo-pendukung">Kos Bu Nining</span>
          </a>
        </div>

        <div class="user-profile" id="userProfile" style="display: none;">
          <a class="btn-icon-notif" href="notifikasi.php" title="Notifikasi" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
              <span class="badge-dot" id="notificationDot" hidden></span>
          </a>
          <span class="header-divider" aria-hidden="true"></span>
          <div class="account">
            <span class="user-avatar" id="headerAvatar"></span>
            <span class="user-name" id="headerName"></span>
          </div>
        </div>
      </header>

      <section class="container">
        <div class="perpanjangan-heading">
          <h1>Perpanjangan Sewa</h1>
          <p>Pilih durasi untuk memperpanjang masa sewa kamu</p>
        </div>

        <div class="card-box empty-state" id="emptyState" hidden>
          <h2>Data sewa belum tersedia</h2>
          <p>Perpanjangan hanya bisa diajukan oleh penghuni yang reservasinya sudah diterima.</p>
          <a href="dashboard.php" class="btn-outline">Kembali ke Dashboard</a>
        </div>

        <form class="card-box form-perpanjangan" id="formPerpanjangan" hidden novalidate>
          <div class="info-sewa">
            <p class="label-kecil">Informasi Kamar &amp; Sewa Saat Ini</p>
            <h2 id="infoKamar">-</h2>
            <p class="info-periode" id="infoPeriode">Masa sewa saat ini -</p>
          </div>

          <fieldset class="grup-pilihan">
            <legend>Pilih Tipe Kamar</legend>
            <div class="pilihan pilihan-4" id="pilihanTipe"></div>
          </fieldset>

          <fieldset class="grup-pilihan">
            <legend>Pilih Durasi Perpanjangan</legend>
            <div class="pilihan pilihan-3">
              <label class="opsi"><input type="radio" name="durasi" value="1"><span>1 Bulan</span></label>
              <label class="opsi"><input type="radio" name="durasi" value="6" checked><span>6 Bulan</span></label>
              <label class="opsi"><input type="radio" name="durasi" value="12"><span>12 Bulan</span></label>
            </div>
          </fieldset>

          <fieldset class="grup-pilihan">
            <legend>Pilih Skema Pembayaran</legend>
            <div class="pilihan pilihan-2">
              <label class="opsi"><input type="radio" name="skema" value="dp" checked><span>DP (Down Payment)</span></label>
              <label class="opsi"><input type="radio" name="skema" value="lunas"><span>Lunas</span></label>
            </div>
          </fieldset>

          <fieldset class="grup-pilihan">
            <legend>Pilih Metode Pembayaran</legend>
            <div class="pilihan pilihan-2">
              <label class="opsi"><input type="radio" name="metode" value="transfer" checked><span>Transfer</span></label>
              <label class="opsi"><input type="radio" name="metode" value="cash"><span>Cash</span></label>
            </div>
          </fieldset>

          <ul class="ringkasan" aria-live="polite">
            <li><svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg><span>Tipe Kamar: <b id="ringTipe">-</b></span></li>
            <li><svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg><span>Masa sewa baru: <b id="ringMasa">-</b></span></li>
            <li><svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg><span>Tipe Pembayaran: <b id="ringSkema">-</b></span></li>
            <li><svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg><span>Metode Pembayaran: <b id="ringMetode">-</b></span></li>
            <li><svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M8 7h8M8 11h8M8 15h5"/></svg><span>Tagihan <b id="ringTotal">-</b></span></li>
            <li id="ringDpBaris"><svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M8 7h8M8 11h8M8 15h5"/></svg><span>Dibayar setelah disetujui (DP 50%): <b id="ringDp">-</b></span></li>
          </ul>

          <div class="aksi-form">
            <button type="submit" class="btn-utama">Ajukan Perpanjangan</button>
          </div>
        </form>
      </section>
    </main>
  </div>

  <script src="../assets/js/penghuni/common.js"></script>
  <script src="../assets/js/calon-penghuni/kamar-data.js"></script>
  <script src="../assets/js/penghuni/perpanjangan-sewa.js"></script>
</body>
</html>
