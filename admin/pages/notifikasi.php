<?php /* Notifikasi · UI Admin SIPENKOS */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi · SIPENKOS Admin</title>
    <link rel="icon" href="../assets/images/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
</head>
<body data-page="notifikasi" data-module="notifikasi">
<div class="app">
    <?php $activeMenu = ''; include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="app-body">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
        <main class="main" id="main">
<div class="page-header">
    <div>
        <h1 class="page-title">Notifikasi</h1>
        <p class="page-subtitle">Riwayat pemberitahuan aktivitas dan pengajuan terbaru dari penghuni</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-secondary btn-sm" data-mark-all>Tandai Semua Dibaca</button>
    </div>
</div>
<div class="list-section" data-list>
    <div class="toolbar">
        <div class="chips" role="group" aria-label="Filter status"><button type="button" class="chip is-active" aria-pressed="true" data-filter="all" data-label="Semua" data-count>Semua</button><button type="button" class="chip" aria-pressed="false" data-filter="unread" data-label="Belum Dibaca" data-count>Belum Dibaca</button><button type="button" class="chip" aria-pressed="false" data-filter="reservasi" data-label="Reservasi">Reservasi</button><button type="button" class="chip" aria-pressed="false" data-filter="pembayaran" data-label="Pembayaran">Pembayaran</button><button type="button" class="chip" aria-pressed="false" data-filter="kerusakan" data-label="Kerusakan">Kerusakan</button></div>    </div>
    <div class="card notif-list">
                    <div class="notif-item is-unread" role="link" tabindex="0" data-item
                 data-status="reservasi unread" data-href="data-reservasi.php">
                <span class="icon-box tone-blue"><i class="icon icon-calendar-check" aria-hidden="true"></i></span>
                <div class="notif-body">
                    <p class="notif-text">Nadia Putri mengajukan reservasi Tipe 1 Non-AC, Kamar Mandi Luar</p>
                    <p class="notif-time"><i class="icon icon-clock" aria-hidden="true"></i>15 Okt 2026 · 10:30</p>
                </div>
                <span class="badge badge-info"><span class="badge-dot"></span><span class="badge-label">Reservasi</span></span>                <span class="notif-unread-dot" aria-label="Belum dibaca"></span>
            </div>
                    <div class="notif-item is-unread" role="link" tabindex="0" data-item
                 data-status="kerusakan unread" data-href="laporan-kerusakan.php">
                <span class="icon-box tone-red"><i class="icon icon-wrench" aria-hidden="true"></i></span>
                <div class="notif-body">
                    <p class="notif-text">Nana melaporkan kerusakan: Lampu kamar mandi mati (Kamar 07)</p>
                    <p class="notif-time"><i class="icon icon-clock" aria-hidden="true"></i>15 Okt 2026 · 08:05</p>
                </div>
                <span class="badge badge-danger"><span class="badge-dot"></span><span class="badge-label">Kerusakan</span></span>                <span class="notif-unread-dot" aria-label="Belum dibaca"></span>
            </div>
                    <div class="notif-item" role="link" tabindex="0" data-item
                 data-status="pembayaran" data-href="detail-bukti-transfer.php">
                <span class="icon-box tone-amber"><i class="icon icon-credit-card" aria-hidden="true"></i></span>
                <div class="notif-body">
                    <p class="notif-text">Siti Nurhaliza mengunggah bukti transfer tagihan Oktober 2026</p>
                    <p class="notif-time"><i class="icon icon-clock" aria-hidden="true"></i>02 Okt 2026 · 14:20</p>
                </div>
                <span class="badge badge-warning"><span class="badge-dot"></span><span class="badge-label">Pembayaran</span></span>                <span class="notif-unread-dot" aria-label="Sudah dibaca"></span>
            </div>
                    <div class="notif-item" role="link" tabindex="0" data-item
                 data-status="kerusakan" data-href="laporan-kerusakan.php">
                <span class="icon-box tone-red"><i class="icon icon-wrench" aria-hidden="true"></i></span>
                <div class="notif-body">
                    <p class="notif-text">Siti Nurhaliza melaporkan kerusakan: AC kurang dingin (Kamar 01)</p>
                    <p class="notif-time"><i class="icon icon-clock" aria-hidden="true"></i>01 Okt 2026 · 14:15</p>
                </div>
                <span class="badge badge-danger"><span class="badge-dot"></span><span class="badge-label">Kerusakan</span></span>                <span class="notif-unread-dot" aria-label="Sudah dibaca"></span>
            </div>
                    <div class="notif-item" role="link" tabindex="0" data-item
                 data-status="pembayaran" data-href="verifikasi-pembayaran.php">
                <span class="icon-box tone-amber"><i class="icon icon-credit-card" aria-hidden="true"></i></span>
                <div class="notif-body">
                    <p class="notif-text">Rina Fitria mengunggah bukti transfer tagihan Oktober 2026</p>
                    <p class="notif-time"><i class="icon icon-clock" aria-hidden="true"></i>01 Okt 2026 · 09:00</p>
                </div>
                <span class="badge badge-warning"><span class="badge-dot"></span><span class="badge-label">Pembayaran</span></span>                <span class="notif-unread-dot" aria-label="Sudah dibaca"></span>
            </div>
                <div class="notif-empty" data-empty hidden>Tidak ada notifikasi pada kategori ini.</div>
    </div>
</div>
        </main>
    </div>
</div>
<!-- Modal konfirmasi keluar -->
<div class="modal" id="modal-logout" role="dialog" aria-modal="true" aria-labelledby="logout-title" hidden>
    <div class="modal-dialog modal-sm modal-center">
        <div class="result-icon result-icon-danger"><i class="icon icon-log-out" aria-hidden="true"></i></div>
        <h2 class="result-title" id="logout-title">Keluar dari Akun?</h2>
        <p class="result-text">Kamu akan keluar dari panel admin Kos Bu Nining.</p>
        <div class="modal-actions modal-actions-split">
            <button type="button" class="btn btn-secondary btn-lg" data-modal-close>Batal</button>
            <a href="../index.php?keluar=1" class="btn btn-danger btn-lg">Keluar</a>
        </div>
    </div>
</div>
<div class="toast-stack" id="toast-stack" aria-live="polite"></div>
<script src="../js/script.js"></script>
</body>
</html>
