<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifikasi - SIPENKOS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/calon-penghuni/style.css">
  <link rel="stylesheet" href="../assets/css/calon-penghuni/notifikasi.css">
</head>
<body>
  <header class="navbar">
    <div class="container navbar-container">
      <a href="beranda.php" class="logo"><span class="logo-judul">SIPENKOS</span><span class="logo-divider">|</span><span class="logo-pendukung">Kos Bu Nining</span></a>
      <nav class="menu">
        <a href="beranda.php" class="nav">Beranda</a>
        <a href="daftar-kamar.php" class="nav">Kamar</a>
        <a href="tentang.php" class="nav">Tentang</a>
      </nav>
      <div class="nav-actions-wrapper">
        <div class="nav-actions" id="guestActions"><a href="registrasi.php" class="btn btn-outline">Daftar</a><a href="login.php" class="btn btn-primary">Masuk</a></div>
        <div class="user-profile" id="userProfile" style="display:none">
          <button type="button" class="btn-icon-notif" title="Notifikasi" aria-label="Notifikasi">🔔<span class="badge-dot"></span></button>
          <div class="user-info"><div class="user-avatar" id="headerAvatar"></div><span class="user-name" id="headerName"></span></div>
        </div>
      </div>
    </div>
  </header>

  <main class="container notifikasi-page">
    <div class="notifikasi-heading">
      <div><p class="eyebrow">PEMBERITAHUAN</p><h1>Notifikasi</h1></div>
      <a href="beranda.php" class="back-home">← Kembali ke Beranda</a>
    </div>
    <div class="daftar-notifikasi" id="daftarNotifikasi" aria-live="polite"></div>
  </main>

  <footer class="footer">
    <div class="logo-judul text-white">SIPENKOS</div>
    <p class="footer-tagline">🛡️ Hunian terpercaya &amp; dikelola langsung oleh pemilik</p>
    <p class="footer-location">Kos Bu Nining · Malang, Jawa Timur</p>
    <div class="copyright">© 2026 SIPENKOS - Kos Bu Nining Malang</div>
  </footer>
  <script src="../assets/js/calon-penghuni/main.js"></script>
  <script src="../assets/js/calon-penghuni/notifikasi.js"></script>
</body>
</html>
