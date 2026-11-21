<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - SIPENKOS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  
    <link rel="stylesheet" href="../assets/css/calon-penghuni/style.css">
    <link rel="stylesheet" href="../assets/css/calon-penghuni/pembayaran.css">
</head>
<body>
    <header class="navbar">
    <div class="container navbar-container">
      <a href="beranda.php" class="logo">
        <span class="logo-judul">SIPENKOS</span>
        <span class="logo-divider">|</span>
        <span class="logo-pendukung">Kos Bu Nining</span>
      </a>

      <div class="nav-actions-wrapper">
        <div class="nav-actions" id="guestActions">
          <a href="registrasi.php" class="btn btn-outline">Daftar</a>
          <a href="login.php" class="btn btn-primary">Masuk</a>
        </div>
        <div class="user-profile" id="userProfile" style="display: none;">
          <button type="button" class="btn-icon-notif" title="Notifikasi" aria-label="Notifikasi">
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

  <main class="section-halaman">
    <div class="container">
      <a href="form-sewa.php" class="back-link">
        ← Kembali ke Formulir Reservasi
      </a>

      <div class="section-header">
        <h1 class="judul-section">Pembayaran Sewa Kamar</h1>
        <p class="deskripsi">Selesaikan pembayaran untuk melanjutkan proses reservasi kamar.
        </p>
      </div>

      <div class="alur-card">
        <div class="alur-sewa completed">
          <span class="nomor">✓</span>
          <span class="alur-label">Formulir Reservasi</span>
        </div>
        <span class="step-arrow">→</span>
        <div class="alur-sewa active">
          <span class="nomor">2</span>
          <span class="alur-label">Pembayaran</span>
        </div>
        <span class="step-arrow">→</span>
        <div class="alur-sewa">
          <span class="nomor">3</span>
          <span class="alur-label">Menunggu Verifikasi</span>
        </div>
      </div>

      <section class="grid-shell"
        aria-label="Rincian dan formulir pembayaran">
        <div class="grid-dua-kolom grid-dua-kolom--kanan-sempit">

          <div class="card-box rincian-pembayaran-box">
            <h2 class="judul-box">Rincian Pembayaran</h2>
            <div class="rincian-list" id="paymentSummary">
              <p class="pesan-memuat">Memuat rincian reservasi...</p>
            </div>

            
            <div class="pilihan-pembayaran">
              <h3 class="subjudul-box">Pilihan Pembayaran</h3>
              <div class="opsi-pembayaran">
                <label class="opsi-item">
                  <input type="radio" name="tipePembayaran"
                  value="dp" checked>
                  
                  <span class="opsi-konten">
                    <strong>DP 50%</strong>
                    <small>Bayar setengah dari total sewa sekarang, sisanya dicicil setiap bulan sesuai durasi sewa</small>
                  </span>
                </label>
                
                <label class="opsi-item">
                  <input type="radio" name="tipePembayaran"
                  value="full">
                  
                  <span class="opsi-konten">
                    <strong>Bayar Penuh</strong>
                    <small>Bayar seluruh biaya sewa sekaligus.</small>
                  </span>
                </label>
              </div>
            </div>
            
            <div class="metode-pembayaran">
              <h3 class="subjudul-box">Metode Pembayaran</h3>
              <div class="opsi-pembayaran">
                <label class="opsi-item">
                  <input type="radio" name="metodePembayaran"
                  value="transfer" checked>
                  
                  <span class="opsi-konten">
                    <strong>Transfer Bank</strong>
                    <small>Pembayaran melalui transfer bank.</small>
                  </span>
                </label>
                
                <label class="opsi-item">
                  <input type="radio" name="metodePembayaran" value="tunai">
                  
                  <span class="opsi-konten">
                    <strong>Tunai di Kos</strong>
                    <small>Pembayaran langsung kepada pengelola.</small>
                  </span>
                </label>
              </div>
            </div>
            <div class="nominal-pembayaran">
              <div class="nominal-item">
                <span>Yang Dibayar Sekarang</span>
                <strong id="paymentAmount">Rp 0</strong>
              </div>
              <div class="nominal-item" id="remainingPayment">
                <span>Sisa Pembayaran</span>
                <strong id="remainingAmount">Rp 0</strong>
              </div>
            </div>

          </div>
          
          <div class="card-box upload-pembayaran-box">
            <h2 class="judul-box">Unggah Bukti Pembayaran</h2>
            <div class="rekening-box" id="bankInfo">
              <div class="rekening-header">
                <strong>Rekening Pembayaran</strong>
                <span>Kos Bu Nining</span>
              </div>

              <div class="rekening-detail">
                <div class="rekening-logo">BCA</div>
                <div class="rekening-nomor">
                  <strong>123 456 7890</strong>
                  <small>a.n. Nining</small>
                </div>

                <button type="button" class="btn btn-outline btn-sm" id="copyAccount"> Salin </button>
              </div>
              <p class="catatan">
                Pastikan nama penerima <strong>Nining</strong> sebelum melakukan transfer.
              </p>
            </div>
            
            <form id="paymentForm">
              <div class="form-group">
                <label class="form-label" for="paymentProof">Bukti Pembayaran</label>

                <div class="upload-area" id="uploadArea">
                  <label for="paymentProof" class="upload-kosong" id="uploadEmpty">
                    <input type="file" id="paymentProof" accept=".jpg,.jpeg,.png,.pdf" hidden>
                    <span class="upload-icon">⇧</span>
                    <strong id="uploadTitle">Klik atau seret file ke sini</strong>
                    <small id="uploadDescription">Format JPG, PNG, atau PDF (maks. 5 MB)</small>
                  </label>

                  <div class="preview-file" id="filePreview" hidden>
                    <img class="preview-gambar" id="previewGambar" alt="Pratinjau bukti pembayaran" hidden>
                    <div class="preview-info">
                      <span class="file-icon" id="fileIcon"></span>
                      <div class="file-info">
                        <strong id="fileName"></strong>
                        <small id="fileSize"></small>
                      </div>
                      <button type="button" class="btn-hapus-file" id="removeFile" aria-label="Hapus file">✕</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="informasi" id="paymentNotice">
                <span>ⓘ</span>
                <p>Bukti transfer akan diperiksa oleh Bu Nining. Hasilnya akan dikirim lewat notifikasi di akun kamu.</p>
              </div>
              <button type="submit" class="btn btn-primary btn-full btn-lg"
                id="submitPayment" disabled>
                Kirim Bukti Pembayaran
              </button>
            </form>
          </div>
        </div>
      </section>
    </div>
  </main>
            
  <footer class="footer">
      <div class="logo-judul text-white">SIPENKOS</div>
      <p class="footer-tagline">🛡️ Hunian terpercaya & dikelola langsung oleh pemilik</p>
      <p class="footer-location">Kos Bu Nining · Malang, Jawa Timur</p>

      <div class="kontak">
        <span>📍 Jl. Pisang Agung III No. 29</span>
        <span>💬 WhatsApp 0854-4421-1076</span>
        <span>⏰ Jam bertamu 07.00 - 22.00 WIB</span>
      </div>

      <div class="copyright">
        © 2026 SIPENKOS - Kos Bu Nining Malang
      </div>
  </footer>
  <script src="../assets/js/calon-penghuni/main.js"></script>
  <script src="../assets/js/calon-penghuni/pembayaran.js"></script>
</body>
</html>