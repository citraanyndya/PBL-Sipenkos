<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembayaran - SIPENKOS</title>

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
              <a class="nav-link active" href="riwayat-pembayaran.php"><span class="nav-icon">◴</span>Riwayat Pembayaran</a>
              <a class="nav-link" href="perpanjangan-sewa.php"><span class="nav-icon">▣</span>Perpanjangan Sewa</a>
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
                    <h1 class="judul-section">Riwayat Pembayaran</h1>
                    <p class="deskripsi">
                        Daftar transaksi pembayaran sewa kamu
                    </p>
                </div>

                <div class="riwayat-card">
                    <div class="table-responsive">
                        <table class="tabel-riwayat">
                            <thead>
                                <tr>
                                    <th>Tanggal Bayar</th>
                                    <th>Periode</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Bukti</th>
                                </tr>
                            </thead>

                            <tbody id="daftarRiwayat">
                                <tr>
                                    <td colspan="7" class="loading-riwayat">
                                        Memuat riwayat pembayaran...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>

        <dialog id="dialogBukti" class="dialog-bukti">
            <div class="dialog-header">
                <h2>Bukti Pembayaran</h2>
                <button type="button" id="tutupBukti" class="btn-tutup">✕</button>
            </div>

            <div id="isiBukti" class="isi-bukti"></div>
        </dialog>
        
    </div>
    <script src="../assets/js/penghuni/penghuni-sesi.js"></script>
<script src="../assets/js/penghuni/riwayat-pembayaran.js"></script>
</body>
</html>