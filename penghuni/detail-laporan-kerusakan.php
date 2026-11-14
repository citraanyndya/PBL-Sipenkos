<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Laporan Kerusakan - SIPENKOS</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../assets/css/penghuni/style.css">
  <link rel="stylesheet" href="../assets/css/penghuni/laporan-kerusakan.css">
</head>
<body data-halaman="detail">
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
        <a href="laporan-kerusakan.php" class="link-kembali">
          <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
          Kembali ke Laporan Kerusakan
        </a>

        <!-- Bila id laporan tidak ditemukan -->
        <div class="card-box kosong" id="tidakDitemukan" hidden>
          <h2>Laporan tidak ditemukan</h2>
          <p>Laporan yang kamu cari tidak ada atau sudah dihapus.</p>
          <a href="laporan-kerusakan.php" class="btn-outline">Lihat Semua Laporan</a>
        </div>

        <div id="kontenDetail" hidden>
          <div class="judul-detail">
            <div class="judul-detail-kiri">
              <h1 id="detailJudul">-</h1>
              <span class="badge-status" id="detailStatus">-</span>
            </div>
            <p class="id-tiket">ID Tiket: <span id="detailId">-</span></p>
          </div>

          <div class="detail-grid">
            <article class="card-box">
              <dl class="info-laporan">
                <div>
                  <dt>Kategori</dt>
                  <dd id="detailKategori">-</dd>
                </div>
                <div>
                  <dt>Lokasi</dt>
                  <dd id="detailLokasi">-</dd>
                </div>
                <div>
                  <dt>Tanggal Lapor</dt>
                  <dd id="detailTanggal">-</dd>
                </div>
              </dl>

              <h2 class="sub-judul">Deskripsi</h2>
              <p class="deskripsi-laporan" id="detailDeskripsi">-</p>

              <div id="blokFoto" hidden>
                <h2 class="sub-judul">Foto Kerusakan</h2>
                <img class="foto-laporan" id="detailFoto" alt="Foto kerusakan">
              </div>

              <p class="catatan-pelapor">
                <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                <span id="detailPelapor">Pengajuan dibuat oleh penghuni</span>
              </p>
            </article>

            <article class="card-box">
              <h2 class="judul-card">Riwayat Penanganan</h2>
              <ol class="timeline" id="timeline"></ol>

              <div class="catatan-pengelola" id="catatanPengelola" hidden>
                <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
                <div>
                  <p class="label-kecil">Catatan Pengelola</p>
                  <p id="catatanIsi">-</p>
                </div>
              </div>
            </article>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script src="../assets/js/penghuni/laporan-kerusakan.js"></script>
</body>
</html>
