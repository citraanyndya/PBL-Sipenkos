<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aturan - SIPENKOS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="../assets/css/penghuni/style.css" />
    <link rel="stylesheet" href="../assets/css/penghuni/aturan.css" />
</head>
<body>
    <div class="aturan-layout">
    <aside class="sidebar" id="sidebar">

      <nav class="sidebar-nav" aria-label="Navigasi utama">
        <a class="nav-link" href="dashboard.php"><span class="nav-icon">▦</span>Dashboard</a>
        <a class="nav-link" href="tagihan.php"><span class="nav-icon">▤</span>Tagihan</a>
        <a class="nav-link" href="riwayat-pembayaran.php"><span class="nav-icon">◴</span>Riwayat Pembayaran</a>
        <a class="nav-link" href="perpanjangan-sewa.php"><span class="nav-icon">▣</span>Perpanjangan Sewa</a>
        <a class="nav-link" href="laporan-kerusakan.php"><span class="nav-icon">⚒</span>Laporan Kerusakan</a>
        <a class="nav-link active" href="aturan.php"><span class="nav-icon">⚑</span>Kebijakan &amp; Aturan Kos</a>
      </nav>

      <div class="sidebar-bottom">
        <button class="logout-button" id="logoutButton" type="button"><span class="nav-icon">⇥</span>Keluar</button>
      </div>
    </aside>

    <main class="main-area">
      <header class="navbar">
        <div class="container navbar-container">
          <a href="beranda.php" class="logo">
            <span class="logo-judul">SIPENKOS</span>
            <span class="logo-divider">|</span>
            <span class="logo-pendukung">Kos Bu Nining</span>
          </a>

          <div class="user-profile" id="userProfile" style="display: none;">
          <button class="btn-icon-notif" title="Notifikasi">
            🔔<span class="badge-dot"></span>
          </button>
          <div class="user-info">
            <div class="user-avatar" id="headerAvatar"></div>
            <span class="user-name" id="headerName"></span>
          </div>
        </div>
    </header>

      <section class="section-halaman">
        <div class="container">
            <div class="section-header">
                <h1 class="judul-section">Aturan Kos Bu Nining</h1>
                <p class="deskripsi">Hunian kos yang tenang, aman, dan nyaman di Malang. Dikelola langsung dengan sepenuh hati oleh Bu Nining untuk mendukung kelancaran studi dan aktivitasmu.</p>
            </div>

            <div class="card-box">
                <div class="aturan-card-header">
                    <h2 class="judul-box">Tata Tertib Kos Bu Nining</h2>
                    <span class="catatan" id="jumlahAturan">0 aturan</span>
                </div>

                <div class="aturan-list" id="aturanList">
                    <p class="pesan-memuat">Memuat aturan kos...</p>
                </div>
            </div>
        </div>
      </section>
    </main>
  </div>
  <div class="sidebar-overlay" id="sidebarOverlay"></div>
  <div class="toast-message" id="toastMessage" role="status" aria-live="polite"></div>
    <script src="../assets/js/calon-penghuni/aturan.js"></script>
</body>
</html>