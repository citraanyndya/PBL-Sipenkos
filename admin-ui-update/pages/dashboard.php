<?php /* Dashboard · UI Admin SIPENKOS */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard · SIPENKOS Admin</title>
    <link rel="icon" href="../assets/images/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
</head>
<body data-page="dashboard">
<div class="app">
    <?php $activeMenu = 'dashboard'; include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="app-body">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
        <main class="main" id="main">
<div class="page-header">
    <div>
        <h1 class="page-title">Selamat datang, Admin</h1>
        <p class="page-subtitle">Ringkasan aktivitas Kos Bu Nining · Oktober 2026</p>
    </div>
</div>
<div class="banner banner-warning" id="dashboard-banner">
    <div class="banner-body">
        <i class="icon icon-triangle-alert" aria-hidden="true"></i>        <span>1 bukti pembayaran dari Siti Nurhaliza menunggu verifikasi</span>
    </div>
    <a href="detail-bukti-transfer.php" class="btn btn-primary btn-md">Verifikasi Sekarang</a>
</div>
<section class="stats stats-4" aria-label="Statistik kamar">
    <div class="card stat">
        <span class="icon-box tone-blue"><i class="icon icon-bed-double" aria-hidden="true"></i></span>
        <div><p class="stat-label">Total Kamar</p><p class="stat-value">20</p></div>
    </div>
    <div class="card stat">
        <span class="icon-box tone-green"><i class="icon icon-users" aria-hidden="true"></i></span>
        <div><p class="stat-label">Kamar Terisi</p><p class="stat-value">14</p></div>
    </div>
    <div class="card stat">
        <span class="icon-box tone-amber"><i class="icon icon-door-open" aria-hidden="true"></i></span>
        <div><p class="stat-label">Kamar Kosong</p><p class="stat-value">6</p></div>
    </div>
    <div class="card stat">
        <span class="icon-box tone-blue"><i class="icon icon-calendar-check" aria-hidden="true"></i></span>
        <div><p class="stat-label">Reservasi Baru</p><p class="stat-value">1</p></div>
    </div>
</section>
<section class="grid-2">
    <div class="card dashboard-card-pad">
        <div class="card-head">
            <h2 class="card-title">Perlu Ditindaklanjuti</h2>
            <span class="badge badge-danger"><span class="badge-dot"></span><span class="badge-label">3 tugas</span></span>        </div>
        <div class="task-list">
                            <div class="task-item">
                    <span class="icon-box icon-box-sm tone-blue"><i class="icon icon-calendar-check" aria-hidden="true"></i></span>
                    <div class="task-body">
                        <p class="task-title">Reservasi baru dari Nadia Putri</p>
                        <p class="task-sub">Tipe 1 Non-AC, Kamar Mandi Luar · 15 Okt 2026</p>
                    </div>
                    <a href="data-reservasi.php" class="btn btn-secondary btn-sm">Tinjau</a>
                </div>
                            <div class="task-item">
                    <span class="icon-box icon-box-sm tone-amber"><i class="icon icon-credit-card" aria-hidden="true"></i></span>
                    <div class="task-body">
                        <p class="task-title">Bukti transfer Siti Nurhaliza</p>
                        <p class="task-sub">Tagihan Oktober 2026 · Rp 500.000</p>
                    </div>
                    <a href="detail-bukti-transfer.php" class="btn btn-secondary btn-sm">Verifikasi</a>
                </div>
                            <div class="task-item">
                    <span class="icon-box icon-box-sm tone-red"><i class="icon icon-wrench" aria-hidden="true"></i></span>
                    <div class="task-body">
                        <p class="task-title">Laporan kerusakan Kamar 01</p>
                        <p class="task-sub">AC kurang dingin · 01 Okt 2026</p>
                    </div>
                    <a href="detail-laporan-kerusakan.php" class="btn btn-secondary btn-sm">Detail</a>
                </div>
                    </div>
    </div>
    <div class="card dashboard-card-pad occupancy-card">
        <div class="card-head">
            <h2 class="card-title">Okupansi per Tipe Kamar</h2>
            <a href="monitor-kamar.php" class="btn btn-ghost btn-sm">Lihat Semua</a>
        </div>
        <div class="occupancy">
                            <div class="occ-item">
                    <div class="occ-head">
                        <span class="occ-name">Tipe 1 Non-AC, KM Luar</span>
                        <span class="occ-count">6/7 terisi</span>
                    </div>
                    <div class="progress" role="progressbar" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100" aria-label="Tipe 1 Non-AC, KM Luar">
                        <div class="progress-fill" data-width="86"></div>
                    </div>
                </div>
                            <div class="occ-item">
                    <div class="occ-head">
                        <span class="occ-name">Tipe 2 Non-AC, KM Dalam</span>
                        <span class="occ-count">3/5 terisi</span>
                    </div>
                    <div class="progress" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" aria-label="Tipe 2 Non-AC, KM Dalam">
                        <div class="progress-fill" data-width="60"></div>
                    </div>
                </div>
                            <div class="occ-item">
                    <div class="occ-head">
                        <span class="occ-name">Tipe 3 AC, KM Luar</span>
                        <span class="occ-count">3/3 terisi</span>
                    </div>
                    <div class="progress" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" aria-label="Tipe 3 AC, KM Luar">
                        <div class="progress-fill is-full" data-width="100"></div>
                    </div>
                </div>
                            <div class="occ-item">
                    <div class="occ-head">
                        <span class="occ-name">Tipe 4 AC, KM Dalam</span>
                        <span class="occ-count">2/5 terisi</span>
                    </div>
                    <div class="progress" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" aria-label="Tipe 4 AC, KM Dalam">
                        <div class="progress-fill" data-width="40"></div>
                    </div>
                </div>
                    </div>
    </div>
</section>
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
