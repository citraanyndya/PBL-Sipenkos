<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Kerusakan - SIPENKOS</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../assets/css/penghuni/style.css">
  <link rel="stylesheet" href="../assets/css/penghuni/laporan-kerusakan.css">
</head>
<body data-halaman="daftar">
  <div class="laporan-layout">
    <aside class="sidebar" id="sidebar">
      <nav class="sidebar-nav" aria-label="Navigasi utama">
        <a class="nav-link" href="dashboard.php"><span class="nav-icon">▦</span>Dashboard</a>
        <a class="nav-link" href="tagihan.php"><span class="nav-icon">▤</span>Tagihan</a>
        <a class="nav-link" href="riwayat-pembayaran.php"><span class="nav-icon">◴</span>Riwayat Pembayaran</a>
        <a class="nav-link" href="perpanjangan-sewa.php"><span class="nav-icon">▣</span>Perpanjangan Sewa</a>
        <a class="nav-link active" href="laporan-kerusakan.php" aria-current="page"><span class="nav-icon">⚒</span>Laporan Kerusakan</a>
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
        <div class="judul-baris">
          <div class="laporan-heading">
            <h1>Laporan Kerusakan</h1>
            <p>Laporkan dan pantau perbaikan fasilitas kamar kamu</p>
          </div>
          <a href="buat-laporan-kerusakan.php" class="btn-utama">
            <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Buat Laporan
          </a>
        </div>

        <div class="filter-status" role="group" aria-label="Filter status laporan" id="filterStatus">
          <button type="button" class="filter-pill aktif" data-filter="Semua" aria-pressed="true">Semua</button>
          <button type="button" class="filter-pill" data-filter="Baru" aria-pressed="false">Baru</button>
          <button type="button" class="filter-pill" data-filter="Diproses" aria-pressed="false">Diproses</button>
          <button type="button" class="filter-pill" data-filter="Selesai" aria-pressed="false">Selesai</button>
        </div>

        <ul class="daftar-laporan" id="daftarLaporan"></ul>

        <div class="card-box kosong" id="kosong" hidden>
          <div class="kosong-ikon" aria-hidden="true">
            <svg class="ikon" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
          </div>
          <h2 id="kosongJudul">Belum ada laporan</h2>
          <p id="kosongPesan">Ada fasilitas kamar yang rusak? Buat laporan agar pemilik kos bisa segera menindaklanjuti.</p>
        </div>
      </section>
    </main>
  </div>

  <script src="../assets/js/penghuni/common.js"></script>
  <script src="../assets/js/penghuni/laporan-kerusakan.js"></script>
</body>
</html>
