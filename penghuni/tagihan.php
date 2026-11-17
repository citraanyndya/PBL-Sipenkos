<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan - SIPENKOS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  
    <link rel="stylesheet" href="../assets/css/penghuni/style.css">
    <link rel="stylesheet" href="../assets/css/penghuni/tagihan.css">
</head>
<body>
    <div class="tagihan-layout">
        <aside class="sidebar" id="sidebar">
            <nav class="sidebar-nav" aria-label="Navigasi utama">
              <a class="nav-link" href="dashboard.php"><span class="nav-icon">▦</span>Dashboard</a>
              <a class="nav-link active" href="tagihan.php"><span class="nav-icon">▤</span>Tagihan</a>
              <a class="nav-link" href="riwayat-pembayaran.php"><span class="nav-icon">◴</span>Riwayat Pembayaran</a>
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

            <div class="user-profile" id="userProfile" style="display: none;">
              <button class="btn-icon-notif" type="button" title="Notifikasi" aria-label="Notifikasi">
                <svg class="ikon-notif" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                <span class="badge-dot"></span>
              </button>
              <span class="header-divider" aria-hidden="true"></span>
              <div class="user-info">
                <div class="user-avatar" id="headerAvatar"></div>
                <span class="user-name" id="headerName"></span>
              </div>
            </div>
          </div>
        </header>

        
        <section class="tagihan-content">
            <div class="tagihan-heading">
                <h1>Tagihan</h1>
                <p>Daftar tagihan sewa kamar kamu</p>
            </div>

            <div class="tagihan-table-wrapper">
                <table class="tagihan-table">
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Total</th>
                            <th>Terbayar</th>
                            <th>Sisa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="tagihanList">
                        <tr>
                            <td colspan="6" class="tagihan-loading">
                                Memuat tagihan...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="modal-upload" id="modalUpload">
            <div class="modal-upload-content">
                <button type="button" class="modal-close" id="tutupModal">&times;</button>

                <h2>Upload Bukti Pembayaran</h2>
                <p class="modal-deskripsi" id="detailTagihan"></p>

                <form id="formUpload">
                    <label for="nominalBayar">Nominal Pembayaran</label>
                    <input type="number" id="nominalBayar" class="input-nominal" min="1" placeholder="Kosongkan = lunasi seluruh sisa">

                    <label for="buktiPembayaran">Bukti Pembayaran</label>
                    <input type="file" id="buktiPembayaran" accept=".jpg,.jpeg,.png,.pdf" required>
                    <small>Format JPG, PNG, atau PDF. Maksimal 5 MB.</small>
                    <div id="previewBukti"></div>
                    <button type="submit" class="btn-kirim-bukti">
                        Kirim untuk Diverifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/api.js"></script>
    <script src="../assets/js/penghuni/penghuni.js"></script>
    <script src="../assets/js/penghuni/tagihan.js"></script>
</body>
</html>