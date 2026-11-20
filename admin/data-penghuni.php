<?php /* Data Penghuni · UI Admin SIPENKOS */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penghuni · SIPENKOS Admin</title>
    <link rel="icon" href="../assets/images/admin/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../assets/css/admin/style.css">
    <link rel="stylesheet" href="../assets/css/admin/responsive.css">
</head>
<body data-page="data-penghuni" data-module="penghuni">
<div class="app">
    <?php $activeMenu = 'penghuni'; include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="app-body">
        <?php include __DIR__ . '/includes/navbar.php'; ?>
        <main class="main" id="main">
<div class="page-header">
    <div>
        <h1 class="page-title">Data Penghuni</h1>
        <p class="page-subtitle">Daftar seluruh penghuni aktif dan calon penyewa kos</p>
    </div>
</div>
<div class="list-section" data-list>
    <div class="toolbar toolbar-end">
                <label class="search"><i class="icon icon-search" aria-hidden="true"></i><span class="sr-only">Cari nama penghuni...</span><input type="search" placeholder="Cari nama penghuni..." value="" data-search autocomplete="off"><button type="button" class="search-clear" aria-label="Hapus pencarian"><i class="icon icon-x" aria-hidden="true"></i></button></label>    </div>
    <div class="card">
        <div class="table-wrap">
            <table class="table table-penghuni">
                <thead>
                <tr>
                    <th>Penghuni</th>
                    <th>Kamar</th>
                    <th>Tipe Kamar</th>
                    <th class="ta-center">Mulai Sewa</th>
                                        <th class="ta-right">Dokumen</th>
                </tr>
                </thead>
                <tbody>
                                    <tr data-item data-group="penghuni" data-status="aktif" data-text="siti nurhaliza 0812–7788–1100 01">
                        <td>
                            <div class="person">
                                <span class="avatar ">SN</span>                                <div><p class="person-name">Siti Nurhaliza</p><p class="person-sub">0812–7788–1100</p></div>
                            </div>
                        </td>
                        <td>01</td>
                        <td class="nowrap">Tipe 1 Non-AC, Kamar Mandi Luar</td>
                        <td class="ta-center nowrap">01 Sep 2026</td>
                                                <td class="ta-right"><button type="button" class="link" data-ktp="0">Lihat KTP</button></td>
                    </tr>
                                    <tr data-item data-group="penghuni" data-status="aktif" data-text="rina fitria 0813–9988–7766 03">
                        <td>
                            <div class="person">
                                <span class="avatar ">RF</span>                                <div><p class="person-name">Rina Fitria</p><p class="person-sub">0813–9988–7766</p></div>
                            </div>
                        </td>
                        <td>03</td>
                        <td class="nowrap">Tipe 3 AC, Kamar Mandi Luar</td>
                        <td class="ta-center nowrap">01 Okt 2026</td>
                                                <td class="ta-right"><button type="button" class="link" data-ktp="1">Lihat KTP</button></td>
                    </tr>
                                    <tr data-item data-group="penghuni" data-status="aktif" data-text="dinda aulia 0857–1122–3344 04">
                        <td>
                            <div class="person">
                                <span class="avatar ">DA</span>                                <div><p class="person-name">Dinda Aulia</p><p class="person-sub">0857–1122–3344</p></div>
                            </div>
                        </td>
                        <td>04</td>
                        <td class="nowrap">Tipe 4 AC, Kamar Mandi Dalam</td>
                        <td class="ta-center nowrap">01 Okt 2026</td>
                                                <td class="ta-right"><button type="button" class="link" data-ktp="2">Lihat KTP</button></td>
                    </tr>
                                    <tr data-item data-group="penghuni" data-status="aktif" data-text="nana 0896–2211–0099 07">
                        <td>
                            <div class="person">
                                <span class="avatar ">N</span>                                <div><p class="person-name">Nana</p><p class="person-sub">0896–2211–0099</p></div>
                            </div>
                        </td>
                        <td>07</td>
                        <td class="nowrap">Tipe 4 AC, Kamar Mandi Dalam</td>
                        <td class="ta-center nowrap">01 Jul 2026</td>
                                                <td class="ta-right"><button type="button" class="link" data-ktp="3">Lihat KTP</button></td>
                    </tr>
                                <tr class="table-empty" data-empty hidden><td colspan="6"><i class="icon icon-search" aria-hidden="true"></i>Penghuni tidak ditemukan.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal dokumen identitas (desain 28) -->
<div class="modal" id="modal-ktp" role="dialog" aria-modal="true" aria-labelledby="ktp-title" hidden>
    <div class="modal-dialog">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="ktp-title">Dokumen Identitas Penghuni</h2>
                <p class="modal-subtitle" data-f="subtitle">Siti Nurhaliza · Kamar 01</p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <div class="doc-layout">
            <div class="media media-zoom" data-f="media" data-zoom="../assets/images/admin/ktp-siti-nurhaliza.jpg" data-zoom-alt="Foto KTP">
                <img src="../assets/images/admin/ktp-siti-nurhaliza.jpg" alt="Foto KTP Siti Nurhaliza">
            </div>
            <div class="detail-list">
                <div class="detail-row"><span class="detail-label">Nama Lengkap</span><span class="detail-value" data-f="name">Siti Nurhaliza</span></div>
                <div class="detail-row"><span class="detail-label">Jenis Dokumen</span><span class="detail-value">KTP</span></div>
                <div class="detail-row"><span class="detail-label">No. HP</span><span class="detail-value" data-f="phone">0812–7788–1100</span></div>
                <div class="detail-row"><span class="detail-label">Kamar</span><span class="detail-value" data-f="room">01 · Tipe 1</span></div>
                <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value" data-f="status"><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Terverifikasi</span></span></span></div>
            </div>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary btn-lg" data-modal-close>Tutup</button>
            <a class="btn btn-primary btn-lg" data-f="download" href="../assets/images/admin/ktp-siti-nurhaliza.jpg" download="KTP-Siti-Nurhaliza.jpg">Unduh Dokumen</a>
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
<script>window.APP_DATA = {"residents":[{"name":"Siti Nurhaliza","phone":"0812–7788–1100","room":"01","typeId":1,"type":"Tipe 1 Non-AC, Kamar Mandi Luar","start":"01 Sep 2026","status":"aktif","ktp":"ktp-siti-nurhaliza.jpg"},{"name":"Rina Fitria","phone":"0813–9988–7766","room":"03","typeId":3,"type":"Tipe 3 AC, Kamar Mandi Luar","start":"01 Okt 2026","status":"aktif","ktp":""},{"name":"Dinda Aulia","phone":"0857–1122–3344","room":"04","typeId":4,"type":"Tipe 4 AC, Kamar Mandi Dalam","start":"01 Okt 2026","status":"aktif","ktp":""},{"name":"Nana","phone":"0896–2211–0099","room":"07","typeId":4,"type":"Tipe 4 AC, Kamar Mandi Dalam","start":"01 Jul 2026","status":"aktif","ktp":""},{"name":"Nadia Putri","phone":"0812–3456–7781","room":"–","typeId":1,"type":"Tipe 1 Non-AC, Kamar Mandi Luar","start":"01 Nov 2026","status":"calon","ktp":""}]};</script>
<script src="../assets/js/admin/script.js"></script>
<script src="../assets/js/common/sesi.js"></script>
</body>
</html>
