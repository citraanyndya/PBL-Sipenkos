<?php /* Laporan Keuangan · UI Admin SIPENKOS */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan · SIPENKOS Admin</title>
    <link rel="icon" href="../assets/images/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
</head>
<body data-page="laporan-keuangan" data-module="keuangan">
<div class="app">
    <?php $activeMenu = 'keuangan'; include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="app-body">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
        <main class="main" id="main">
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Keuangan</h1>
        <p class="page-subtitle">Pantau pendapatan, pengeluaran operasional, dan pendapatan bersih kos</p>
    </div>
    <div class="page-actions">
        <div class="dropdown dropdown-select" data-dropdown data-period-select data-current="2026-10"><button type="button" class="select-btn" data-dropdown-toggle aria-haspopup="listbox" aria-expanded="false"><i class="icon icon-calendar" aria-hidden="true"></i><span data-dropdown-label>Oktober 2026</span><i class="icon icon-chevron-down" aria-hidden="true"></i></button><div class="dropdown-menu" role="listbox"><button type="button" class="dropdown-item is-selected" role="option" data-value="2026-10">Oktober 2026</button><button type="button" class="dropdown-item" role="option" data-value="2026-09">September 2026</button></div></div>        <button type="button" class="btn btn-secondary btn-sm" data-download-report>Unduh Laporan</button>
    </div>
</div>
<section class="stats stats-3" aria-label="Ringkasan keuangan">
    <div class="card stat stat-money">
        <div class="stat-top"><span class="icon-box tone-green"><i class="icon icon-trending-up" aria-hidden="true"></i></span><p class="stat-label">Pendapatan Kotor</p></div>
        <div><p class="stat-value" data-fin="gross">Rp 10.050.000</p><p class="stat-note" data-fin="gross-note">Dari 14 kamar terisi</p></div>
    </div>
    <div class="card stat stat-money">
        <div class="stat-top"><span class="icon-box tone-red"><i class="icon icon-trending-down" aria-hidden="true"></i></span><p class="stat-label">Total Pengeluaran</p></div>
        <div><p class="stat-value" data-fin="expense">Rp 3.000.000</p><p class="stat-note" data-fin="expense-note">4 transaksi operasional</p></div>
    </div>
    <div class="card stat stat-money">
        <div class="stat-top"><span class="icon-box tone-blue"><i class="icon icon-wallet" aria-hidden="true"></i></span><p class="stat-label">Pendapatan Bersih</p></div>
        <div><p class="stat-value stat-value-primary" data-fin="net">Rp 7.050.000</p><p class="stat-note">Pendapatan kotor − pengeluaran</p></div>
    </div>
</section>
<div class="card">
    <div class="card-head card-head-bar">
        <h2 class="card-title">Pengeluaran Operasional</h2>
        <button type="button" class="btn btn-primary btn-sm" data-new-expense>+ Tambah Pengeluaran</button>
    </div>
    <div class="table-wrap">
        <table class="table table-keuangan">
            <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th class="ta-right">Nominal</th>
            </tr>
            </thead>
            <tbody id="expense-body">
                            <tr>
                    <td class="nowrap">01 Okt 2026</td>
                    <td><span class="badge badge-neutral"><span class="badge-dot"></span><span class="badge-label">Operasional Rutin</span></span></td>
                    <td>Biaya listrik induk kos</td>
                    <td class="ta-right nowrap">Rp 1.200.000</td>
                </tr>
                            <tr>
                    <td class="nowrap">01 Okt 2026</td>
                    <td><span class="badge badge-neutral"><span class="badge-dot"></span><span class="badge-label">Operasional Rutin</span></span></td>
                    <td>Biaya Wi-Fi induk kos</td>
                    <td class="ta-right nowrap">Rp 500.000</td>
                </tr>
                            <tr>
                    <td class="nowrap">01 Okt 2026</td>
                    <td><span class="badge badge-neutral"><span class="badge-dot"></span><span class="badge-label">Operasional Rutin</span></span></td>
                    <td>Biaya kebersihan dan penjaga kos</td>
                    <td class="ta-right nowrap">Rp 1.000.000</td>
                </tr>
                            <tr>
                    <td class="nowrap">05 Okt 2026</td>
                    <td><span class="badge badge-amber"><span class="badge-dot"></span><span class="badge-label">Perbaikan Fasilitas</span></span></td>
                    <td>Servis AC Kamar 01 (laporan Siti Nurhaliza)</td>
                    <td class="ta-right nowrap">Rp 300.000</td>
                </tr>
                        </tbody>
            <tfoot>
            <tr>
                <td>Total</td>
                <td></td>
                <td></td>
                <td class="ta-right nowrap" id="expense-total">Rp 3.000.000</td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<!-- Pop-up Tambah Pengeluaran Operasional -->
<div class="modal" id="modal-pengeluaran" role="dialog" aria-modal="true" aria-labelledby="exp-title" hidden>
    <div class="modal-dialog modal-form">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="exp-title">Tambah Pengeluaran Operasional</h2>
                <p class="modal-subtitle modal-subtitle-lg">Masukkan rincian pengeluaran kos baru</p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <form id="form-pengeluaran" class="form-grid" novalidate>
            <div class="form-grid form-grid-2">
                <div class="field">
                    <label class="field-label" for="exp-date">Tanggal Transaksi</label>
                    <input class="input" type="date" id="exp-date" value="2026-10-01" min="2026-09-01" max="2026-10-31" data-required>
                    <span class="field-error" hidden>Tanggal wajib diisi</span>
                </div>
                <div class="field">
                    <label class="field-label" for="exp-category">Kategori Pengeluaran</label>
                    <select class="select" id="exp-category" data-required>
                        <option value="rutin" selected>Operasional Rutin</option>
                        <option value="perbaikan">Perbaikan Fasilitas</option>
                    </select>
                </div>
            </div>
            <div class="field">
                <label class="field-label" for="exp-desc">Keterangan Pengeluaran</label>
                <input class="input" type="text" id="exp-desc" placeholder="Contoh: Biaya air kos" value="" maxlength="80" data-required>
                <span class="field-error" hidden>Keterangan wajib diisi</span>
            </div>
            <div class="field">
                <label class="field-label" for="exp-amount">Nominal (Rp)</label>
                <div class="input-prefix"><span>Rp.</span><input class="input" type="text" id="exp-amount" inputmode="numeric" placeholder="0" value="" data-required></div>
                <span class="field-error" hidden>Nominal wajib diisi</span>
            </div>
            <div class="modal-actions modal-actions-form">
                <button type="button" class="btn btn-text btn-lg" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary btn-lg">Simpan Pengeluaran</button>
            </div>
        </form>
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
<script>window.APP_DATA = {"finance": {"periods": {"2026-10": {"label": "Oktober 2026", "gross": 10050000, "filled": 14, "expenses": [{"date": "2026-10-01", "category": "rutin", "desc": "Biaya listrik induk kos", "amount": 1200000, "created": "2026-10-01T08:05:00"}, {"date": "2026-10-01", "category": "rutin", "desc": "Biaya Wi-Fi induk kos", "amount": 500000, "created": "2026-10-01T08:20:00"}, {"date": "2026-10-01", "category": "rutin", "desc": "Biaya kebersihan dan penjaga kos", "amount": 1000000, "created": "2026-10-01T08:40:00"}, {"date": "2026-10-05", "category": "perbaikan", "desc": "Servis AC Kamar 01 (laporan Siti Nurhaliza)", "amount": 300000, "created": "2026-10-05T14:10:00"}]}, "2026-09": {"label": "September 2026", "gross": 9200000, "filled": 13, "expenses": [{"date": "2026-09-01", "category": "rutin", "desc": "Biaya listrik induk kos", "amount": 1150000, "created": "2026-09-01T08:05:00"}, {"date": "2026-09-01", "category": "rutin", "desc": "Biaya Wi-Fi induk kos", "amount": 500000, "created": "2026-09-01T08:20:00"}, {"date": "2026-09-01", "category": "rutin", "desc": "Biaya kebersihan dan penjaga kos", "amount": 1000000, "created": "2026-09-01T08:40:00"}, {"date": "2026-09-22", "category": "perbaikan", "desc": "Perbaikan keran wastafel Kamar 03", "amount": 150000, "created": "2026-09-22T15:25:00"}]}}}};</script>
<script src="../js/script.js"></script>
</body>
</html>
