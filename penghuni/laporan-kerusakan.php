<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kerusakan - SIPENKOS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  
    <link rel="stylesheet" href="../assets/css/penghuni/style.css">
    <link rel="stylesheet" href="../assets/css/penghuni/riwayat-pembayaran.css">
</head>
<body>
    <div class="tagihan-layout">
        <aside class="sidebar" id="sidebar">
            <nav class="sidebar-nav" aria-label="Navigasi utama">
              <a class="nav-link" href="dashboard.php"><span class="nav-icon">▦</span>Dashboard</a>
              <a class="nav-link" href="tagihan.php"><span class="nav-icon">▤</span>Tagihan</a>
              <a class="nav-link" href="riwayat-pembayaran.php"><span class="nav-icon">◴</span>Riwayat Pembayaran</a>
              <a class="nav-link" href="perpanjangan-sewa.php"><span class="nav-icon">▣</span>Perpanjangan Sewa</a>
              <a class="nav-link active" href="laporan-kerusakan.php"><span class="nav-icon">⚒</span>Laporan Kerusakan</a>
              <a class="nav-link" href="notifikasi.php"><span class="nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg></span>Notifikasi</a>
              <a class="nav-link" href="aturan.php"><span class="nav-icon">⚑</span>Kebijakan &amp; Aturan Kos</a>
            </nav>
                <div class="sidebar-bottom">
                <button class="logout-button" id="logoutButton" type="button"><span class="nav-icon">⇥</span>Keluar</button>
            </div>
        </aside>

        <main class="main-area">
        <header class="navbar">
          <div class="container navbar-container">
            <a href="dashboard-penghuni.php" class="logo">
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
              <button class="account-toggle" id="accountToggle" type="button" aria-haspopup="true" aria-expanded="false">
                <span class="user-avatar" id="headerAvatar"></span>
                <span class="user-name" id="headerName"></span>
                <svg class="chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
              </button>
              <div class="account-menu" id="accountMenu" hidden>
                <button type="button" data-logout><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>Keluar</button>
              </div>
            </div>
          </div>
        </header>

        
    </div>
<script src="../assets/js/penghuni/common.js"></script>
  </body>
</html>