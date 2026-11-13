<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kamar - SIPENKOS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  
    <link rel="stylesheet" href="../assets/css/penghuni/style.css">
    <link rel="stylesheet" href="../assets/css/penghuni/detail-kamar.css">
</head>
<body>
    <section class="section-halaman">
      <div class="container">
        <a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>

        <div class="detail-header">
          <h1 class="tipe-kamar">Memuat Detail Kamar...</h1>
        </div>

        <div class="detail-grid">
            <div class="galeri-detail">
                <div class="card-galeri">
                    <img src="" alt="Foto Utama Kamar" class="foto-utama">
                </div>
        
                <div class="galeri-tambahan"
                     aria-label="Foto tambahan kamar">
                </div>
            </div>
        </div>

        <div class="card-box deskripsi-box">
          <h2 class="judul-box">Deskripsi Kamar</h2>
          <p class="deskripsi-kamar">Memuat deskripsi...</p>

          <div class="fasilitas-wrapper">
            <h3 class="subjudul-box">Fasilitas Kamar</h3>
            <ul class="fasilitas"></ul>
          </div>

          <div class="fasilitas-wrapper">
            <h3 class="subjudul-box">Fasilitas Bersama & Umum</h3>
            <ul class="fasilitas">
              <li class="item-fasilitas">
                <span class="icon">📶</span> <span>Wi-Fi</span>
              </li>
              <li class="item-fasilitas">
                <span class="icon">🍳</span> <span>Dapur Bersama</span>
              </li>
              <li class="item-fasilitas">
                <span class="icon">⚡</span> <span>Listrik Token</span>
              </li>
              <li class="item-fasilitas">
                <span class="icon">🛵</span> <span>Parkir Motor</span>
              </li>
              <li class="item-fasilitas">
                <span class="icon">📹</span> <span>CCTV 24 Jam</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <script src="../assets/js/calon-penghuni/kamar-data.js"></script>
    <script src="../assets/js/calon-penghuni/detail-kamar.js"></script>
</body>
</html>