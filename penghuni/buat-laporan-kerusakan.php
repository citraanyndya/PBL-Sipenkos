<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Laporan Kerusakan - SIPENKOS</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../assets/css/penghuni/style.css">
  <link rel="stylesheet" href="../assets/css/penghuni/laporan-kerusakan.css">
</head>
<body data-halaman="buat">
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
        <a href="laporan-kerusakan.php" class="link-kembali link-kembali--biru">
          <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
          Kembali ke Laporan Kerusakan
        </a>

        <div class="laporan-heading">
          <h1>Buat Laporan Baru</h1>
          <p>Jelaskan kerusakan agar pemilik kos bisa segera menindaklanjuti</p>
        </div>

        <form class="card-box form-laporan" id="formLaporan" novalidate>
          <div class="field">
            <label for="lokasi">Lokasi</label>
            <div class="input-kunci">
              <input type="text" id="lokasi" value="Kamar kamu" readonly>
              <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/><path d="M12 15v2"/></svg>
            </div>
          </div>

          <div class="field">
            <label for="kategori">Kategori</label>
            <div class="input-select">
              <select id="kategori" name="kategori" required aria-describedby="kategoriError">
                <option value="" selected disabled>Pilih Kategori</option>
                <option value="Air/Kamar Mandi">Air/Kamar Mandi</option>
                <option value="Listrik">Listrik</option>
                <option value="Lainnya">Lainnya</option>
              </select>
              <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <p class="pesan-error" id="kategoriError" hidden>Pilih kategori kerusakan.</p>
          </div>

          <div class="field">
            <label for="judul">Judul Kerusakan</label>
            <input type="text" id="judul" name="judul" maxlength="80" placeholder="Contoh: Keran air wastafel patah" required aria-describedby="judulError">
            <p class="pesan-error" id="judulError" hidden>Tulis judul kerusakan.</p>
          </div>

          <div class="field">
            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="5" maxlength="1000" placeholder="Jelaskan detail kerusakan, waktu kejadian, atau kendala yang dialami..." required aria-describedby="deskripsiError"></textarea>
            <p class="pesan-error" id="deskripsiError" hidden>Jelaskan detail kerusakannya.</p>
          </div>

          <div class="field">
            <span class="label" id="labelFoto">Foto Kerusakan (Opsional)</span>
            <label class="unggah" id="areaUnggah" for="foto">
              <input type="file" id="foto" accept="image/jpeg,image/png" aria-labelledby="labelFoto" aria-describedby="fotoError">
              <span class="unggah-ikon" aria-hidden="true">
                <svg class="ikon" viewBox="0 0 24 24"><path d="M12 15V4M7 9l5-5 5 5"/><path d="M5 20h14"/></svg>
              </span>
              <span class="unggah-teks">Klik untuk unggah foto atau seret berkas ke sini</span>
              <span class="unggah-format">Format JPG, PNG (Maksimal 5 MB)</span>
            </label>

            <div class="pratinjau" id="pratinjau" hidden>
              <img id="pratinjauGambar" alt="Pratinjau foto kerusakan">
              <div class="pratinjau-info">
                <span id="pratinjauNama">foto.jpg</span>
                <button type="button" class="tombol-teks" id="hapusFoto">Hapus foto</button>
              </div>
            </div>
            <p class="pesan-error" id="fotoError" hidden></p>
          </div>

          <div class="aksi-form">
            <a href="laporan-kerusakan.php" class="tombol-batal">Batal</a>
            <button type="submit" class="btn-utama">
              <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
              Kirim Laporan
            </button>
          </div>
        </form>
      </section>
    </main>
  </div>

  <script src="../assets/js/penghuni/common.js"></script>
  <script src="../assets/js/penghuni/laporan-kerusakan.js"></script>
</body>
</html>
