<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - SIPENKOS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/beranda.css" />
    <link rel="stylesheet" href="assets/css/kamar.css" />
</head>
<body>
    <header class="navbar">
      <div class="container navbar-container">
        <a href="beranda.php" class="logo">
          <span class="logo-judul">SIPENKOS</span>
          <span class="logo-divider">|</span>
          <span class="logo-pendukung">Kos Bu Nining</span>
        </a>

        <nav class="menu">
          <a href="beranda.php" class="nav active">Beranda</a>
          <a href="daftar-kamar.php" class="nav">Kamar</a>
          <a href="tentang.php" class="nav">Tentang</a>
        </nav>

        <div class="nav-actions-wrapper">
          <div class="nav-actions" id="guestActions">
            <a href="registrasi.php" class="btn btn-outline">Daftar</a>
            <a href="login.php" class="btn btn-primary">Masuk</a>
          </div>

          <div class="user-profile" id="userProfile" style="display: none">
            <button class="btn-icon-notif" title="Notifikasi">
              🔔<span class="badge-dot"></span>
            </button>
            <div class="user-info">
              <div class="user-avatar" id="headerAvatar"></div>
              <span class="user-name" id="headerName"></span>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div id="banner-verifikasi" class="home-banner banner-warning">
      <div class="banner-content">
        <span class="icon">⏱️</span>
        <p><strong>Reservasi kamar kamu sedang di verifikasi.</strong> Cek detailnya di status reservasi.</p>
      </div>
      <a href="status-reservasi.html" class="btn-banner">Lihat Status &rarr;</a>
    </div>

    <div id="banner-hasil" class="home-banner banner-info hidden">
      <div class="banner-content">
        <span class="icon">ℹ️</span>
        <p><strong>Status reservasi kamu telah diperbarui.</strong> Silakan cek detail hasilnya.</p>
      </div>
      <a href="status-reservasi.html" class="btn-banner">Cek Hasil Reservasi &rarr;</a>
    </div>
    
</body>
</html>