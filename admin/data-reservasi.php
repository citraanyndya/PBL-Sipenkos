<?php /* Data Reservasi · UI Admin SIPENKOS */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Reservasi · SIPENKOS Admin</title>
    <link rel="icon" href="../assets/images/admin/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../assets/css/admin/style.css">
    <link rel="stylesheet" href="../assets/css/admin/responsive.css">
</head>
<body data-page="data-reservasi" data-module="reservasi">
<div class="app">
    <?php $activeMenu = 'reservasi'; include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="app-body">
        <?php include __DIR__ . '/includes/navbar.php'; ?>
        <main class="main" id="main">
<div class="page-header">
    <div>
        <h1 class="page-title">Data Reservasi</h1>
        <p class="page-subtitle">Daftar pengajuan pemesanan kamar dari calon penghuni</p>
    </div>
</div>
<div class="list-section" data-list>
    <div class="toolbar toolbar-end">
        <label class="search"><i class="icon icon-search" aria-hidden="true"></i><span class="sr-only">Cari nama calon penghuni...</span><input type="search" placeholder="Cari nama calon penghuni..." value="" data-search autocomplete="off"><button type="button" class="search-clear" aria-label="Hapus pencarian"><i class="icon icon-x" aria-hidden="true"></i></button></label>    </div>
    <div class="card">
        <div class="table-wrap">
            <table class="table table-reservasi">
                <thead>
                <tr>
                    <th class="th-wrap">Tanggal<br>Pengajuan</th>
                    <th>Calon Penghuni</th>
                    <th>Tipe Kamar</th>
                    <th>Durasi</th>
                    <th>Tipe Bayar</th>
                    <th>Status</th>
                    <th class="ta-right">Aksi</th>
                </tr>
                </thead>
                <tbody>
                                    <tr data-item data-group="reservasi" data-id="R-1015" data-status="menunggu" data-text="nadia putri 0812–3456–7781 tipe 1 non-ac, kamar mandi luar">
                        <td class="nowrap">15 Okt 2026</td>
                        <td>
                            <div class="person">
                                <span class="avatar ">NP</span>                                <div><p class="person-name">Nadia Putri</p><p class="person-sub">0812–3456–7781</p></div>
                            </div>
                        </td>
                        <td class="cell-wrap col-type">Tipe 1 Non-AC, Kamar Mandi Luar</td>
                        <td class="nowrap">6 Bulan</td>
                        <td><span class="pill pill-dp">Down Payment</span></td>
                        <td data-badge><span class="badge badge-warning"><span class="badge-dot"></span><span class="badge-label">Menunggu</span></span></td>
                        <td>
                            <div class="row-actions" data-when="menunggu">
                                <button type="button" class="btn btn-primary btn-sm" data-res-action="terima">Terima</button>
                                <button type="button" class="btn btn-danger-outline btn-sm" data-res-action="tolak">Tolak</button>
                            </div>
                            <div class="row-actions" data-when="diterima ditolak">
                                <button type="button" class="btn btn-secondary btn-sm" data-res-action="detail">Detail</button>
                            </div>
                        </td>
                    </tr>
                                    <tr data-item data-group="reservasi" data-id="R-0928" data-status="diterima" data-text="dinda aulia 0857–1122–3344 tipe 4 ac, kamar mandi dalam">
                        <td class="nowrap">28 Sep 2026</td>
                        <td>
                            <div class="person">
                                <span class="avatar ">DA</span>                                <div><p class="person-name">Dinda Aulia</p><p class="person-sub">0857–1122–3344</p></div>
                            </div>
                        </td>
                        <td class="cell-wrap col-type">Tipe 4 AC, Kamar Mandi Dalam</td>
                        <td class="nowrap">12 Bulan</td>
                        <td><span class="pill pill-full">Lunas (Full)</span></td>
                        <td data-badge><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Diterima</span></span></td>
                        <td>
                            <div class="row-actions" data-when="menunggu">
                                <button type="button" class="btn btn-primary btn-sm" data-res-action="terima">Terima</button>
                                <button type="button" class="btn btn-danger-outline btn-sm" data-res-action="tolak">Tolak</button>
                            </div>
                            <div class="row-actions" data-when="diterima ditolak">
                                <button type="button" class="btn btn-secondary btn-sm" data-res-action="detail">Detail</button>
                            </div>
                        </td>
                    </tr>
                                    <tr data-item data-group="reservasi" data-id="R-0920" data-status="diterima" data-text="rina fitria 0813–9988–7766 tipe 3 ac, kamar mandi luar">
                        <td class="nowrap">20 Sep 2026</td>
                        <td>
                            <div class="person">
                                <span class="avatar ">RF</span>                                <div><p class="person-name">Rina Fitria</p><p class="person-sub">0813–9988–7766</p></div>
                            </div>
                        </td>
                        <td class="cell-wrap col-type">Tipe 3 AC, Kamar Mandi Luar</td>
                        <td class="nowrap">6 Bulan</td>
                        <td><span class="pill pill-full">Lunas (Full)</span></td>
                        <td data-badge><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Diterima</span></span></td>
                        <td>
                            <div class="row-actions" data-when="menunggu">
                                <button type="button" class="btn btn-primary btn-sm" data-res-action="terima">Terima</button>
                                <button type="button" class="btn btn-danger-outline btn-sm" data-res-action="tolak">Tolak</button>
                            </div>
                            <div class="row-actions" data-when="diterima ditolak">
                                <button type="button" class="btn btn-secondary btn-sm" data-res-action="detail">Detail</button>
                            </div>
                        </td>
                    </tr>
                                    <tr data-item data-group="reservasi" data-id="R-0912" data-status="ditolak" data-text="bima pratama 0821–5566–4433 tipe 3 ac, kamar mandi luar">
                        <td class="nowrap">12 Sep 2026</td>
                        <td>
                            <div class="person">
                                <span class="avatar ">BP</span>                                <div><p class="person-name">Bima Pratama</p><p class="person-sub">0821–5566–4433</p></div>
                            </div>
                        </td>
                        <td class="cell-wrap col-type">Tipe 3 AC, Kamar Mandi Luar</td>
                        <td class="nowrap">1 Bulan</td>
                        <td><span class="pill pill-full">Lunas (Full)</span></td>
                        <td data-badge><span class="badge badge-danger"><span class="badge-dot"></span><span class="badge-label">Ditolak</span></span></td>
                        <td>
                            <div class="row-actions" data-when="menunggu">
                                <button type="button" class="btn btn-primary btn-sm" data-res-action="terima">Terima</button>
                                <button type="button" class="btn btn-danger-outline btn-sm" data-res-action="tolak">Tolak</button>
                            </div>
                            <div class="row-actions" data-when="diterima ditolak">
                                <button type="button" class="btn btn-secondary btn-sm" data-res-action="detail">Detail</button>
                            </div>
                        </td>
                    </tr>
                                <tr class="table-empty" data-empty hidden><td colspan="7"><i class="icon icon-search" aria-hidden="true"></i>Tidak ada reservasi yang cocok.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal detail reservasi -->
<div class="modal" id="modal-reservasi" role="dialog" aria-modal="true" aria-labelledby="res-title" hidden>
    <div class="modal-dialog modal-md">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="res-title">Detail Reservasi</h2>
                <p class="modal-subtitle" data-f="subtitle"></p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <div class="person person-lg">
            <span class="avatar avatar-lg" data-f="avatar"></span>
            <div><p class="person-name" data-f="name"></p><p class="person-sub" data-f="contact"></p></div>
        </div>
        <div class="detail-list mt-16">
            <div class="detail-row"><span class="detail-label">Tipe Kamar</span><span class="detail-value" data-f="type"></span></div>
            <div class="detail-row"><span class="detail-label">Durasi Sewa</span><span class="detail-value" data-f="duration"></span></div>
            <div class="detail-row"><span class="detail-label">Rencana Mulai</span><span class="detail-value" data-f="start"></span></div>
            <div class="detail-row"><span class="detail-label">Tipe Bayar</span><span class="detail-value" data-f="pay"></span></div>
            <div class="detail-row"><span class="detail-label">Nominal Dibayar</span><span class="detail-value" data-f="amount"></span></div>
            <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value" data-f="status"></span></div>
        </div>
        <div class="modal-actions" data-f="actions-pending">
            <button type="button" class="btn btn-danger-outline btn-lg" data-modal-decide="tolak">Tolak</button>
            <button type="button" class="btn btn-primary btn-lg" data-modal-decide="terima">Terima Reservasi</button>
        </div>
        <div class="modal-actions" data-f="actions-done" hidden>
            <button type="button" class="btn btn-secondary btn-lg" data-modal-close>Tutup</button>
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
<script>window.APP_DATA = {"reservations":[{"id":"R-1015","date":"15 Okt 2026","name":"Nadia Putri","phone":"0812–3456–7781","type":"Tipe 1 Non-AC, Kamar Mandi Luar","duration":"6 Bulan","pay":"dp","status":"menunggu","start":"01 Nov 2026","email":"nadia.putri@gmail.com","amount":1500000},{"id":"R-0928","date":"28 Sep 2026","name":"Dinda Aulia","phone":"0857–1122–3344","type":"Tipe 4 AC, Kamar Mandi Dalam","duration":"12 Bulan","pay":"full","status":"diterima","start":"01 Okt 2026","email":"dinda.aulia@gmail.com","amount":11400000},{"id":"R-0920","date":"20 Sep 2026","name":"Rina Fitria","phone":"0813–9988–7766","type":"Tipe 3 AC, Kamar Mandi Luar","duration":"6 Bulan","pay":"full","status":"diterima","start":"01 Okt 2026","email":"rina.fitria@gmail.com","amount":4800000},{"id":"R-0912","date":"12 Sep 2026","name":"Bima Pratama","phone":"0821–5566–4433","type":"Tipe 3 AC, Kamar Mandi Luar","duration":"1 Bulan","pay":"full","status":"ditolak","start":"15 Sep 2026","email":"bima.pratama@gmail.com","amount":800000}]};</script>
<script src="../assets/js/admin/script.js"></script>
<script src="../assets/js/common/sesi.js"></script>
</body>
</html>
