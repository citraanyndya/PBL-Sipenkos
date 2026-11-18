<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - SIPENKOS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/penghuni/style.css">
    <link rel="stylesheet" href="../assets/css/penghuni/notifikasi.css">
</head>
<body>
    <div class="notifikasi-layout">
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
                    <a class="btn-icon-notif" href="javascript:history.back()" title="Kembali" aria-label="Kembali">
                      <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6 8a6 6 0 1 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                      </svg>
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
                <div class="notif-heading">
                    <h1>Notifikasi</h1>
                    <button type="button" class="btn-tandai-semua" id="tandaiSemua">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 7 17l-5-5"/><path d="m22 10-7.5 7.5L13 16"/></svg>
                        Tandai semua sudah dibaca
                    </button>
                </div>

                <div class="notif-card">
                    <div class="notif-daftar" id="daftarNotifikasi" aria-live="polite"></div>
                </div>
            </section>
        </main>
    </div>

    <script src="../assets/js/penghuni/common.js"></script>
    <script src="../assets/js/penghuni/notifikasi.js"></script>
</body>
</html>
