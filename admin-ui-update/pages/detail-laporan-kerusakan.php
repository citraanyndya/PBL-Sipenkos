<?php /* Detail Laporan Kerusakan · UI Admin SIPENKOS */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan Kerusakan · SIPENKOS Admin</title>
    <link rel="icon" href="../assets/images/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
</head>
<body data-page="detail-laporan-kerusakan" data-module="kerusakan" data-open-report="K-1001" data-preset-status="selesai" data-preset-note="Keran  sudah diperbaiki, silakan dicek kembali.">
<div class="app">
    <?php $activeMenu = 'kerusakan'; include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="app-body">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
        <main class="main" id="main">
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Kerusakan</h1>
        <p class="page-subtitle">Daftar laporan fasilitas rusak dari penghuni yang perlu ditindaklanjuti</p>
    </div>
</div>
<div data-banner-slot></div>
<div class="banner banner-danger banner-tight" data-alert-new>
    <div class="banner-body"><i class="icon icon-triangle-alert" aria-hidden="true"></i><span data-alert-count>1 laporan baru belum dikerjakan</span></div>
    <button type="button" class="btn btn-secondary btn-sm" data-show-new>Lihat Laporan</button>
</div>
<div class="list-section" data-list>
    <div class="toolbar">
        <div class="chips" role="group" aria-label="Filter status"><button type="button" class="chip is-active" aria-pressed="true" data-filter="all" data-label="Semua" data-count>Semua</button><button type="button" class="chip" aria-pressed="false" data-filter="belum" data-label="Belum Dikerjakan" data-count>Belum Dikerjakan</button><button type="button" class="chip" aria-pressed="false" data-filter="diproses" data-label="Diproses" data-count>Diproses</button><button type="button" class="chip" aria-pressed="false" data-filter="selesai" data-label="Selesai" data-count>Selesai</button></div>        <label class="search search-xs"><i class="icon icon-search" aria-hidden="true"></i><span class="sr-only">Cari laporan...</span><input type="search" placeholder="Cari laporan..." value="" data-search autocomplete="off"><button type="button" class="search-clear" aria-label="Hapus pencarian"><i class="icon icon-x" aria-hidden="true"></i></button></label>    </div>
    <div class="card">
        <div class="table-wrap">
            <table class="table table-kerusakan">
                <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pelapor</th>
                    <th>Rincian Laporan</th>
                    <th>Status</th>
                    <th class="ta-right">Aksi</th>
                </tr>
                </thead>
                <tbody>
                                    <tr data-item data-group="kerusakan" data-id="K-1015" data-status="diproses" data-text="nana kamar 07 lampu kamar mandi mati listrik &amp; lampu">
                        <td class="nowrap">15 Okt 2026</td>
                        <td>
                            <div class="person">
                                <span class="avatar ">N</span>                                <div><p class="person-name">Nana</p><p class="person-sub">Kamar 07</p></div>
                            </div>
                        </td>
                        <td><p class="cell-main">Lampu kamar mandi mati</p><p class="cell-sub">Listrik &amp; Lampu</p></td>
                        <td data-badge><span class="badge badge-info"><span class="badge-dot"></span><span class="badge-label">Diproses</span></span></td>
                        <td class="ta-right"><button type="button" class="btn btn-secondary btn-sm" data-report="K-1015">Detail</button></td>
                    </tr>
                                    <tr data-item data-group="kerusakan" data-id="K-1001" data-status="belum" data-text="siti nurhaliza kamar 01 keran kamar mandi bocor air/kamar mandi">
                        <td class="nowrap">01 Okt 2026</td>
                        <td>
                            <div class="person">
                                <span class="avatar ">SN</span>                                <div><p class="person-name">Siti Nurhaliza</p><p class="person-sub">Kamar 01</p></div>
                            </div>
                        </td>
                        <td><p class="cell-main">Keran Kamar mandi bocor</p><p class="cell-sub">Air/Kamar mandi</p></td>
                        <td data-badge><span class="badge badge-danger"><span class="badge-dot"></span><span class="badge-label">Belum Dikerjakan</span></span></td>
                        <td class="ta-right"><button type="button" class="btn btn-secondary btn-sm" data-report="K-1001">Detail</button></td>
                    </tr>
                                    <tr data-item data-group="kerusakan" data-id="K-0928" data-status="selesai" data-text="rina fitria kamar 03 keran air bocor air &amp; sanitasi">
                        <td class="nowrap">28 Sep 2026</td>
                        <td>
                            <div class="person">
                                <span class="avatar ">RF</span>                                <div><p class="person-name">Rina Fitria</p><p class="person-sub">Kamar 03</p></div>
                            </div>
                        </td>
                        <td><p class="cell-main">Keran air bocor</p><p class="cell-sub">Air &amp; Sanitasi</p></td>
                        <td data-badge><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Selesai</span></span></td>
                        <td class="ta-right"><button type="button" class="btn btn-secondary btn-sm" data-report="K-0928">Detail</button></td>
                    </tr>
                                    <tr data-item data-group="kerusakan" data-id="K-0920" data-status="selesai" data-text="dinda aulia kamar 04 lampu kamar mati listrik &amp; lampu">
                        <td class="nowrap">20 Sep 2026</td>
                        <td>
                            <div class="person">
                                <span class="avatar ">DA</span>                                <div><p class="person-name">Dinda Aulia</p><p class="person-sub">Kamar 04</p></div>
                            </div>
                        </td>
                        <td><p class="cell-main">Lampu kamar mati</p><p class="cell-sub">Listrik &amp; Lampu</p></td>
                        <td data-badge><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Selesai</span></span></td>
                        <td class="ta-right"><button type="button" class="btn btn-secondary btn-sm" data-report="K-0920">Detail</button></td>
                    </tr>
                                <tr class="table-empty" data-empty hidden><td colspan="5"><i class="icon icon-search" aria-hidden="true"></i>Tidak ada laporan yang cocok.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal ubah status (desain 38) -->
<div class="modal" id="modal-kerusakan" role="dialog" aria-modal="true" aria-labelledby="kr-title" hidden>
    <div class="modal-dialog modal-xl">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="kr-title">Detail Laporan Kerusakan</h2>
                <p class="modal-subtitle" data-f="subtitle"></p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <div class="report-layout">
            <div>
                <div class="media" data-f="media" data-zoom-alt="Foto kerusakan"></div>
                <p class="media-caption">Foto dari penghuni</p>
            </div>
            <div>
                <div class="detail-list">
                    <div class="detail-row"><span class="detail-label">Kategori</span><span class="detail-value" data-f="category"></span></div>
                    <div class="detail-row"><span class="detail-label">Status Saat Ini</span><span class="detail-value" data-f="status"></span></div>
                </div>
                <div class="report-desc">
                    <p class="report-desc-label">Deskripsi</p>
                    <p class="report-desc-text" data-f="desc"></p>
                </div>
            </div>
        </div>
        <div class="modal-section">
            <span class="field-label" id="seg-label">Ubah Status</span>
            <div class="segmented" role="radiogroup" aria-labelledby="seg-label">
                <button type="button" role="radio" data-value="belum">Belum Dikerjakan</button>
                <button type="button" role="radio" data-value="diproses">Diproses</button>
                <button type="button" role="radio" data-value="selesai">Selesai</button>
            </div>
        </div>
        <div class="modal-section">
            <label class="field-label" for="kr-note">Catatan untuk Penghuni (opsional)</label>
            <textarea class="textarea" id="kr-note" rows="2" data-f="note" placeholder="Tulis catatan untuk penghuni..."></textarea>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary btn-lg" data-modal-close>Batal</button>
            <button type="button" class="btn btn-primary btn-lg" data-f="save">Simpan Status</button>
        </div>
    </div>
</div>
<!-- Modal detail selesai (desain 39b) -->
<div class="modal" id="modal-kerusakan-view" role="dialog" aria-modal="true" aria-labelledby="krv-title" hidden>
    <div class="modal-dialog modal-xl">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="krv-title">Detail Laporan Kerusakan</h2>
                <p class="modal-subtitle" data-f="subtitle"></p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <div class="report-layout">
            <div>
                <div class="media" data-f="media" data-zoom-alt="Foto kerusakan"></div>
                <p class="media-caption">Foto dari penghuni</p>
            </div>
            <div>
                <div class="detail-list">
                    <div class="detail-row"><span class="detail-label">Kategori</span><span class="detail-value" data-f="category"></span></div>
                    <div class="detail-row"><span class="detail-label">Status Saat Ini</span><span class="detail-value" data-f="status"></span></div>
                </div>
                <div class="report-desc">
                    <p class="report-desc-label">Deskripsi</p>
                    <p class="report-desc-text" data-f="desc"></p>
                </div>
            </div>
        </div>
        <div class="modal-section">
            <span class="field-label">Catatan untuk Penghuni (opsional)</span>
            <div class="readonly-box" data-f="note"></div>
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
            <a href="../index.php?keluar=1" class="btn btn-danger btn-lg">Keluar</a>
        </div>
    </div>
</div>
<div class="toast-stack" id="toast-stack" aria-live="polite"></div>
<script>window.APP_DATA = {"reports":[{"id":"K-1015","date":"15 Okt 2026","name":"Nana","room":"07","title":"Lampu kamar mandi mati","category":"Listrik \u0026 Lampu","status":"diproses","desc":"Lampu di kamar mandi tidak menyala sejak semalam, sudah dicoba ganti saklar tetapi tetap mati.","photo":"","note":"Teknisi dijadwalkan datang besok pagi."},{"id":"K-1001","date":"01 Okt 2026","name":"Siti Nurhaliza","room":"01","title":"Keran Kamar mandi bocor","category":"Air\/Kamar mandi","status":"belum","desc":"Keran di kamar mandi menetes terus walaupun sudah ditutup rapat.","photo":"foto-keran-bocor.jpg","note":""},{"id":"K-0928","date":"28 Sep 2026","name":"Rina Fitria","room":"03","title":"Keran air bocor","category":"Air \u0026 Sanitasi","status":"selesai","desc":"Keran wastafel di dekat dapur bocor di bagian sambungan pipa.","photo":"","note":"Sambungan pipa sudah diganti, silakan dicek kembali."},{"id":"K-0920","date":"20 Sep 2026","name":"Dinda Aulia","room":"04","title":"Lampu kamar mati","category":"Listrik \u0026 Lampu","status":"selesai","desc":"Lampu utama kamar mati dan berkedip sebelum padam.","photo":"","note":"Lampu sudah diganti dengan yang baru."}]};</script>
<script src="../js/script.js"></script>
</body>
</html>
