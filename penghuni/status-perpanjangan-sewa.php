<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Perpanjangan Sewa - SIPENKOS</title>

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

          <div class="user-profile">
            <button class="btn-icon-notif" type="button" title="Notifikasi" aria-label="Notifikasi">
              <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
              <span class="badge-dot"></span>
            </button>
            <span class="header-divider" aria-hidden="true"></span>
            <div class="user-info">
              <div class="user-avatar" id="headerAvatar">P</div>
              <span class="user-name" id="headerName">Penghuni</span>
            </div>
          </div>
        </div>
      </header>

      <section class="container">
        <div class="page-heading">
          <h1>Status Perpanjangan Sewa</h1>
          <p>Pantau pengajuan perpanjangan sewa kamu</p>
        </div>

        <!-- Ditampilkan bila belum ada pengajuan -->
        <div class="card-box empty-state" id="emptyState" hidden>
          <h2>Belum ada pengajuan perpanjangan</h2>
          <p>Ajukan perpanjangan untuk melanjutkan masa sewa kamu.</p>
          <a href="perpanjangan-sewa.php" class="btn-utama">Ajukan Perpanjangan</a>
        </div>

        <div id="kontenStatus" hidden>
          <!-- Banner status: kelas status-banner--menunggu / --disetujui / --ditolak -->
          <div class="status-banner status-banner--menunggu" id="statusBanner" role="status">
            <span class="status-pill" id="statusPill">Menunggu Verifikasi</span>
            <p id="statusPesan">Pengajuan perpanjangan sedang diperiksa pemilik kos.</p>
            <svg class="ikon status-ikon" id="statusIkon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
          </div>

          <div class="status-grid">
            <!-- Detail pengajuan -->
            <article class="card-box detail-card">
              <h2 class="judul-card">Detail Pengajuan</h2>

              <dl class="panel-detail">
                <div><dt>Kamar</dt><dd id="detailKamar">-</dd></div>
                <div><dt>Durasi</dt><dd id="detailDurasi">-</dd></div>
                <div><dt>Skema Pembayaran</dt><dd id="detailSkema">-</dd></div>
                <div><dt>Metode Pembayaran</dt><dd id="detailMetode">-</dd></div>
                <div><dt>Masa sewa baru</dt><dd id="detailMasa">-</dd></div>
                <div class="panel-pemisah"><dt>Total Tagihan</dt><dd id="detailTotal">-</dd></div>
                <div class="baris-rapat"><dt>Jumlah dibayar</dt><dd id="detailDibayar">-</dd></div>
                <div class="baris-rapat"><dt>Sisa Tagihan</dt><dd id="detailSisa">-</dd></div>
              </dl>

              <!-- Tombol aksi: muncul saat disetujui (bayar) atau ditolak (ajukan ulang) -->
              <div class="aksi-detail" id="aksiDetail" hidden>
                <!-- TODO backend: arahkan tombol bayar ke halaman pembayaran tagihan perpanjangan -->
                <a href="#" class="btn-utama" id="tombolAksi">Bayar DP Sekarang</a>
              </div>

              <div class="detail-footer">
                <span class="nomor-permohonan">
                  <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 16v-5M12 8h.01"/></svg>
                  No. Permohonan: <span id="detailNomor">-</span>
                </span>
                <span>Diajukan secara mandiri</span>
              </div>
            </article>

            <!-- Proses perpanjangan -->
            <article class="card-box">
              <h2 class="judul-card">Proses Perpanjangan</h2>
              <ol class="timeline" id="timeline"></ol>
            </article>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script src="../assets/js/penghuni/perpanjangan-sewa.js"></script>
</body>
</html>
