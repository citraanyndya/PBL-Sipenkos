<?php /* Monitor Kamar · UI Admin SIPENKOS */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Kamar · SIPENKOS Admin</title>
    <link rel="icon" href="../assets/images/admin/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../assets/css/admin/style.css">
    <link rel="stylesheet" href="../assets/css/admin/responsive.css">
</head>
<body data-page="monitor-kamar" data-module="kamar">
<div class="app">
    <?php $activeMenu = 'kamar'; include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="app-body">
        <?php include __DIR__ . '/includes/navbar.php'; ?>
        <main class="main" id="main">
<div class="page-header">
    <div>
        <h1 class="page-title">Monitor Kamar</h1>
        <p class="page-subtitle">Pantau ketersediaan dan tingkat okupansi kamar kos</p>
    </div>
</div>
<section class="stats stats-3" aria-label="Ringkasan kamar">
    <div class="card stat stat-row">
        <span class="icon-box icon-box-lg tone-blue"><i class="icon icon-bed-double" aria-hidden="true"></i></span>
        <div><p class="stat-label">Total Kamar</p><p class="stat-value" data-stat="total">20</p></div>
    </div>
    <div class="card stat stat-row">
        <span class="icon-box icon-box-lg tone-green"><i class="icon icon-users" aria-hidden="true"></i></span>
        <div><p class="stat-label">Kamar Terisi</p><p class="stat-value" data-stat="filled">14</p></div>
    </div>
    <div class="card stat stat-row">
        <span class="icon-box icon-box-lg tone-amber"><i class="icon icon-door-open" aria-hidden="true"></i></span>
        <div><p class="stat-label">Kamar Kosong</p><p class="stat-value" data-stat="empty">6</p></div>
    </div>
</section>
<div class="card">
    <div class="card-head card-head-bar">
        <h2 class="card-title">Ketersediaan per Tipe Kamar</h2>
        <button type="button" class="btn btn-primary btn-sm" data-kelola="3">Kelola Ketersediaan</button>
    </div>
    <div class="table-wrap">
        <table class="table table-kamar">
            <thead>
            <tr>
                <th>Tipe Kamar</th>
                <th>Harga / Bulan</th>
                <th>Total</th>
                <th>Terisi</th>
                <th>Kosong</th>
                <th class="ta-center">Status</th>
                            </tr>
            </thead>
            <tbody>
                            <tr data-room-row="0">
                    <td class="nowrap">Tipe 1 Non-AC, Kamar Mandi Luar</td>
                    <td class="nowrap">Rp 500.000</td>
                    <td data-c="total">7</td>
                    <td data-c="filled">6</td>
                    <td data-c="empty">1</td>
                    <td data-c="status" class="ta-center"><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Tersedia · sisa 1</span></span></td>
                                    </tr>
                            <tr data-room-row="1">
                    <td class="nowrap">Tipe 2 Non-AC, Kamar Mandi Dalam</td>
                    <td class="nowrap">Rp 650.000</td>
                    <td data-c="total">5</td>
                    <td data-c="filled">3</td>
                    <td data-c="empty">2</td>
                    <td data-c="status" class="ta-center"><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Tersedia · sisa 2</span></span></td>
                                    </tr>
                            <tr data-room-row="2">
                    <td class="nowrap">Tipe 3 AC, Kamar Mandi Luar</td>
                    <td class="nowrap">Rp 800.000</td>
                    <td data-c="total">3</td>
                    <td data-c="filled">3</td>
                    <td data-c="empty">0</td>
                    <td data-c="status" class="ta-center"><span class="badge badge-danger"><span class="badge-dot"></span><span class="badge-label">Penuh</span></span></td>
                                    </tr>
                            <tr data-room-row="3">
                    <td class="nowrap">Tipe 4 AC, Kamar Mandi Dalam</td>
                    <td class="nowrap">Rp 950.000</td>
                    <td data-c="total">5</td>
                    <td data-c="filled">2</td>
                    <td data-c="empty">3</td>
                    <td data-c="status" class="ta-center"><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Tersedia · sisa 3</span></span></td>
                                    </tr>
                        </tbody>
        </table>
    </div>
</div>
<!-- Modal Kelola Ketersediaan (desain 30) -->
<div class="modal" id="modal-kamar" role="dialog" aria-modal="true" aria-labelledby="kamar-title" data-initial="3" hidden>
    <div class="modal-dialog modal-md">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="kamar-title">Kelola Ketersediaan Kamar</h2>
                <p class="modal-subtitle">Perubahan langsung tampil di halaman Daftar Kamar</p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <div class="form-grid">
            <div class="field">
                <label class="field-label" for="kelola-tipe">Tipe Kamar</label>
                <select class="select" id="kelola-tipe">
                                            <option value="0">Tipe 1 Non-AC, Kamar Mandi Luar</option>
                                            <option value="1">Tipe 2 Non-AC, Kamar Mandi Dalam</option>
                                            <option value="2">Tipe 3 AC, Kamar Mandi Luar</option>
                                            <option value="3" selected>Tipe 4 AC, Kamar Mandi Dalam</option>
                                    </select>
            </div>
            <div class="form-grid form-grid-2">
                <div class="field">
                    <label class="field-label" for="kelola-total">Total Kamar</label>
                    <div class="stepper">
                        <button type="button" data-step="-1" data-target="kelola-total" aria-label="Kurangi total kamar"><i class="icon icon-minus" aria-hidden="true"></i></button>
                        <input type="number" id="kelola-total" min="1" max="99" value="5" inputmode="numeric">
                        <button type="button" data-step="1" data-target="kelola-total" aria-label="Tambah total kamar"><i class="icon icon-plus" aria-hidden="true"></i></button>
                    </div>
                </div>
                <div class="field">
                    <label class="field-label" for="kelola-terisi">Kamar Terisi</label>
                    <div class="stepper">
                        <button type="button" data-step="-1" data-target="kelola-terisi" aria-label="Kurangi kamar terisi"><i class="icon icon-minus" aria-hidden="true"></i></button>
                        <input type="number" id="kelola-terisi" min="0" max="99" value="2" inputmode="numeric">
                        <button type="button" data-step="1" data-target="kelola-terisi" aria-label="Tambah kamar terisi"><i class="icon icon-plus" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
            <div class="note-box note-info" data-f="info"><i class="icon icon-info" aria-hidden="true"></i><span>Kamar kosong otomatis dihitung: 3 kamar · status Tersedia</span></div>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary btn-lg" data-modal-close>Batal</button>
            <button type="button" class="btn btn-primary btn-lg" data-f="save">Simpan Perubahan</button>
        </div>
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
            <a href="index.php?keluar=1" class="btn btn-danger btn-lg">Keluar</a>
        </div>
    </div>
</div>
<div class="toast-stack" id="toast-stack" aria-live="polite"></div>
<script>window.APP_DATA = {"roomTypes":[{"id":1,"name":"Tipe 1 Non-AC, Kamar Mandi Luar","short":"Tipe 1 Non-AC, KM Luar","price":500000,"total":7,"filled":6},{"id":2,"name":"Tipe 2 Non-AC, Kamar Mandi Dalam","short":"Tipe 2 Non-AC, KM Dalam","price":650000,"total":5,"filled":3},{"id":3,"name":"Tipe 3 AC, Kamar Mandi Luar","short":"Tipe 3 AC, KM Luar","price":800000,"total":3,"filled":3},{"id":4,"name":"Tipe 4 AC, Kamar Mandi Dalam","short":"Tipe 4 AC, KM Dalam","price":950000,"total":5,"filled":2}]};</script>
<script src="../assets/js/admin/script.js"></script>
<script src="../assets/js/common/sesi.js"></script>
</body>
</html>
