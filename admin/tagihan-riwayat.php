<?php /* Tagihan & Riwayat · UI Admin SIPENKOS */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan &amp; Riwayat · SIPENKOS Admin</title>
    <link rel="icon" href="../assets/images/admin/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../assets/css/admin/style.css">
    <link rel="stylesheet" href="../assets/css/admin/responsive.css">
</head>
<body data-page="tagihan-riwayat" data-module="tagihan">
<div class="app">
    <?php $activeMenu = 'tagihan'; include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="app-body">
        <?php include __DIR__ . '/includes/navbar.php'; ?>
        <main class="main" id="main">
<div class="page-header">
    <div>
        <h1 class="page-title">Tagihan &amp; Riwayat Pembayaran</h1>
        <p class="page-subtitle">Pantau status tagihan bulanan dan riwayat transaksi penghuni</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-secondary btn-sm" data-massal>Kirim Pengingat Massal</button>
    </div>
</div>
<div class="list-section" data-list data-hide-empty-chips>
    <div class="toolbar">
        <div class="chips" role="group" aria-label="Filter status"><button type="button" class="chip is-active" aria-pressed="true" data-filter="all" data-label="Semua" data-count>Semua</button><button type="button" class="chip" aria-pressed="false" data-filter="belum" data-label="Belum Dibayar" data-count>Belum Dibayar</button><button type="button" class="chip" aria-pressed="false" data-filter="menunggu" data-label="Menunggu" data-count>Menunggu</button><button type="button" class="chip" aria-pressed="false" data-filter="lunas" data-label="Lunas" data-count>Lunas</button></div>        <div class="toolbar-right">
            <div class="dropdown dropdown-select" data-dropdown data-period-select data-current="2026-10"><button type="button" class="select-btn" data-dropdown-toggle aria-haspopup="listbox" aria-expanded="false"><i class="icon icon-calendar" aria-hidden="true"></i><span data-dropdown-label>Oktober 2026</span><i class="icon icon-chevron-down" aria-hidden="true"></i></button><div class="dropdown-menu" role="listbox"><button type="button" class="dropdown-item is-selected" role="option" data-value="2026-10">Oktober 2026</button><button type="button" class="dropdown-item" role="option" data-value="2026-09">September 2026</button><button type="button" class="dropdown-item" role="option" data-value="2026-08">Agustus 2026</button><button type="button" class="dropdown-item" role="option" data-value="2026-07">Juli 2026</button><button type="button" class="dropdown-item" role="option" data-value="all">Semua Periode</button></div></div>            <label class="search search-md"><i class="icon icon-search" aria-hidden="true"></i><span class="sr-only">Cari nama penghuni...</span><input type="search" placeholder="Cari nama penghuni..." value="" data-search autocomplete="off"><button type="button" class="search-clear" aria-label="Hapus pencarian"><i class="icon icon-x" aria-hidden="true"></i></button></label>        </div>
    </div>
    <div data-banner-slot></div>
    <div class="card">
        <div class="table-wrap">
            <table class="table table-tagihan">
                <thead>
                <tr>
                    <th>Penghuni</th>
                    <th>Tagihan</th>
                    <th>Jatuh Tempo</th>
                    <th>Tanggal Bayar</th>
                    <th>Status</th>
                    <th class="ta-right">Aksi</th>
                </tr>
                </thead>
                <tbody>
                                    <tr data-item data-group="tagihan" data-status="belum" data-period="2026-10"
                        data-text="nana kamar 07"
                        data-name="Nana" data-room="07" data-type="Tipe 4 AC, Kamar Mandi Dalam"
                        data-amount="Rp 950.000" data-period-label="Oktober 2026"
                        data-due="27 Okt 2026" data-paid="">
                        <td>
                            <div class="person">
                                <span class="avatar ">N</span>                                <div>
                                    <button type="button" class="person-name person-link" data-person-filter="Nana" title="Lihat riwayat Nana">Nana</button>
                                    <p class="person-sub">Kamar 07</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="cell-main">Rp 950.000</p><p class="cell-sub">Oktober 2026</p></td>
                        <td class="nowrap">27 Okt 2026</td>
                        <td class="nowrap"><span class="text-muted">-</span></td>
                        <td data-badge><span class="badge badge-danger"><span class="badge-dot"></span><span class="badge-label">Belum Dibayar</span></span></td>
                        <td>
                            <div class="row-actions" data-when="belum"><button type="button" class="btn btn-primary btn-sm" data-tagih>Tagih</button></div>
                            <div class="row-actions" data-when="menunggu"><a href="detail-bukti-transfer.php" class="btn btn-secondary btn-sm">Verifikasi</a></div>
                            <div class="row-actions" data-when="lunas"><button type="button" class="btn btn-secondary btn-sm" data-bill-detail>Detail</button></div>
                        </td>
                    </tr>
                                    <tr data-item data-group="tagihan" data-status="menunggu" data-period="2026-10"
                        data-text="siti nurhaliza kamar 01"
                        data-name="Siti Nurhaliza" data-room="01" data-type="Tipe 1 Non-AC, Kamar Mandi Luar"
                        data-amount="Rp 500.000" data-period-label="Oktober 2026"
                        data-due="27 Okt 2026" data-paid="02 Okt 2026">
                        <td>
                            <div class="person">
                                <span class="avatar ">SN</span>                                <div>
                                    <button type="button" class="person-name person-link" data-person-filter="Siti Nurhaliza" title="Lihat riwayat Siti Nurhaliza">Siti Nurhaliza</button>
                                    <p class="person-sub">Kamar 01</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="cell-main">Rp 500.000</p><p class="cell-sub">Oktober 2026</p></td>
                        <td class="nowrap">27 Okt 2026</td>
                        <td class="nowrap">02 Okt 2026</td>
                        <td data-badge><span class="badge badge-warning"><span class="badge-dot"></span><span class="badge-label">Menunggu</span></span></td>
                        <td>
                            <div class="row-actions" data-when="belum"><button type="button" class="btn btn-primary btn-sm" data-tagih>Tagih</button></div>
                            <div class="row-actions" data-when="menunggu"><a href="detail-bukti-transfer.php" class="btn btn-secondary btn-sm">Verifikasi</a></div>
                            <div class="row-actions" data-when="lunas"><button type="button" class="btn btn-secondary btn-sm" data-bill-detail>Detail</button></div>
                        </td>
                    </tr>
                                    <tr data-item data-group="tagihan" data-status="lunas" data-period="2026-10"
                        data-text="rina fitria kamar 03"
                        data-name="Rina Fitria" data-room="03" data-type="Tipe 3 AC, Kamar Mandi Luar"
                        data-amount="Rp 800.000" data-period-label="Oktober 2026"
                        data-due="27 Okt 2026" data-paid="01 Okt 2026">
                        <td>
                            <div class="person">
                                <span class="avatar ">RF</span>                                <div>
                                    <button type="button" class="person-name person-link" data-person-filter="Rina Fitria" title="Lihat riwayat Rina Fitria">Rina Fitria</button>
                                    <p class="person-sub">Kamar 03</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="cell-main">Rp 800.000</p><p class="cell-sub">Oktober 2026</p></td>
                        <td class="nowrap">27 Okt 2026</td>
                        <td class="nowrap">01 Okt 2026</td>
                        <td data-badge><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Lunas</span></span></td>
                        <td>
                            <div class="row-actions" data-when="belum"><button type="button" class="btn btn-primary btn-sm" data-tagih>Tagih</button></div>
                            <div class="row-actions" data-when="menunggu"><a href="detail-bukti-transfer.php" class="btn btn-secondary btn-sm">Verifikasi</a></div>
                            <div class="row-actions" data-when="lunas"><button type="button" class="btn btn-secondary btn-sm" data-bill-detail>Detail</button></div>
                        </td>
                    </tr>
                                    <tr data-item data-group="tagihan" data-status="lunas" data-period="2026-10"
                        data-text="dinda aulia kamar 04"
                        data-name="Dinda Aulia" data-room="04" data-type="Tipe 4 AC, Kamar Mandi Dalam"
                        data-amount="Rp 950.000" data-period-label="Oktober 2026"
                        data-due="27 Okt 2026" data-paid="30 Sep 2026">
                        <td>
                            <div class="person">
                                <span class="avatar ">DA</span>                                <div>
                                    <button type="button" class="person-name person-link" data-person-filter="Dinda Aulia" title="Lihat riwayat Dinda Aulia">Dinda Aulia</button>
                                    <p class="person-sub">Kamar 04</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="cell-main">Rp 950.000</p><p class="cell-sub">Oktober 2026</p></td>
                        <td class="nowrap">27 Okt 2026</td>
                        <td class="nowrap">30 Sep 2026</td>
                        <td data-badge><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Lunas</span></span></td>
                        <td>
                            <div class="row-actions" data-when="belum"><button type="button" class="btn btn-primary btn-sm" data-tagih>Tagih</button></div>
                            <div class="row-actions" data-when="menunggu"><a href="detail-bukti-transfer.php" class="btn btn-secondary btn-sm">Verifikasi</a></div>
                            <div class="row-actions" data-when="lunas"><button type="button" class="btn btn-secondary btn-sm" data-bill-detail>Detail</button></div>
                        </td>
                    </tr>
                                    <tr data-item data-group="tagihan" data-status="lunas" data-period="2026-09"
                        data-text="nana kamar 07"
                        data-name="Nana" data-room="07" data-type="Tipe 4 AC, Kamar Mandi Dalam"
                        data-amount="Rp 950.000" data-period-label="September 2026"
                        data-due="27 Sep 2026" data-paid="25 Sep 2026">
                        <td>
                            <div class="person">
                                <span class="avatar ">N</span>                                <div>
                                    <button type="button" class="person-name person-link" data-person-filter="Nana" title="Lihat riwayat Nana">Nana</button>
                                    <p class="person-sub">Kamar 07</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="cell-main">Rp 950.000</p><p class="cell-sub">September 2026</p></td>
                        <td class="nowrap">27 Sep 2026</td>
                        <td class="nowrap">25 Sep 2026</td>
                        <td data-badge><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Lunas</span></span></td>
                        <td>
                            <div class="row-actions" data-when="belum"><button type="button" class="btn btn-primary btn-sm" data-tagih>Tagih</button></div>
                            <div class="row-actions" data-when="menunggu"><a href="detail-bukti-transfer.php" class="btn btn-secondary btn-sm">Verifikasi</a></div>
                            <div class="row-actions" data-when="lunas"><button type="button" class="btn btn-secondary btn-sm" data-bill-detail>Detail</button></div>
                        </td>
                    </tr>
                                    <tr data-item data-group="tagihan" data-status="lunas" data-period="2026-09"
                        data-text="siti nurhaliza kamar 01"
                        data-name="Siti Nurhaliza" data-room="01" data-type="Tipe 1 Non-AC, Kamar Mandi Luar"
                        data-amount="Rp 500.000" data-period-label="September 2026"
                        data-due="27 Sep 2026" data-paid="20 Sep 2026">
                        <td>
                            <div class="person">
                                <span class="avatar ">SN</span>                                <div>
                                    <button type="button" class="person-name person-link" data-person-filter="Siti Nurhaliza" title="Lihat riwayat Siti Nurhaliza">Siti Nurhaliza</button>
                                    <p class="person-sub">Kamar 01</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="cell-main">Rp 500.000</p><p class="cell-sub">September 2026</p></td>
                        <td class="nowrap">27 Sep 2026</td>
                        <td class="nowrap">20 Sep 2026</td>
                        <td data-badge><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Lunas</span></span></td>
                        <td>
                            <div class="row-actions" data-when="belum"><button type="button" class="btn btn-primary btn-sm" data-tagih>Tagih</button></div>
                            <div class="row-actions" data-when="menunggu"><a href="detail-bukti-transfer.php" class="btn btn-secondary btn-sm">Verifikasi</a></div>
                            <div class="row-actions" data-when="lunas"><button type="button" class="btn btn-secondary btn-sm" data-bill-detail>Detail</button></div>
                        </td>
                    </tr>
                                    <tr data-item data-group="tagihan" data-status="lunas" data-period="2026-08"
                        data-text="nana kamar 07"
                        data-name="Nana" data-room="07" data-type="Tipe 4 AC, Kamar Mandi Dalam"
                        data-amount="Rp 950.000" data-period-label="Agustus 2026"
                        data-due="27 Agu 2026" data-paid="26 Agu 2026">
                        <td>
                            <div class="person">
                                <span class="avatar ">N</span>                                <div>
                                    <button type="button" class="person-name person-link" data-person-filter="Nana" title="Lihat riwayat Nana">Nana</button>
                                    <p class="person-sub">Kamar 07</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="cell-main">Rp 950.000</p><p class="cell-sub">Agustus 2026</p></td>
                        <td class="nowrap">27 Agu 2026</td>
                        <td class="nowrap">26 Agu 2026</td>
                        <td data-badge><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Lunas</span></span></td>
                        <td>
                            <div class="row-actions" data-when="belum"><button type="button" class="btn btn-primary btn-sm" data-tagih>Tagih</button></div>
                            <div class="row-actions" data-when="menunggu"><a href="detail-bukti-transfer.php" class="btn btn-secondary btn-sm">Verifikasi</a></div>
                            <div class="row-actions" data-when="lunas"><button type="button" class="btn btn-secondary btn-sm" data-bill-detail>Detail</button></div>
                        </td>
                    </tr>
                                    <tr data-item data-group="tagihan" data-status="lunas" data-period="2026-07"
                        data-text="nana kamar 07"
                        data-name="Nana" data-room="07" data-type="Tipe 4 AC, Kamar Mandi Dalam"
                        data-amount="Rp 950.000" data-period-label="Juli 2026"
                        data-due="27 Jul 2026" data-paid="27 Jul 2026">
                        <td>
                            <div class="person">
                                <span class="avatar ">N</span>                                <div>
                                    <button type="button" class="person-name person-link" data-person-filter="Nana" title="Lihat riwayat Nana">Nana</button>
                                    <p class="person-sub">Kamar 07</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="cell-main">Rp 950.000</p><p class="cell-sub">Juli 2026</p></td>
                        <td class="nowrap">27 Jul 2026</td>
                        <td class="nowrap">27 Jul 2026</td>
                        <td data-badge><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Lunas</span></span></td>
                        <td>
                            <div class="row-actions" data-when="belum"><button type="button" class="btn btn-primary btn-sm" data-tagih>Tagih</button></div>
                            <div class="row-actions" data-when="menunggu"><a href="detail-bukti-transfer.php" class="btn btn-secondary btn-sm">Verifikasi</a></div>
                            <div class="row-actions" data-when="lunas"><button type="button" class="btn btn-secondary btn-sm" data-bill-detail>Detail</button></div>
                        </td>
                    </tr>
                                <tr class="table-empty" data-empty hidden><td colspan="6"><i class="icon icon-search" aria-hidden="true"></i>Tidak ada tagihan yang cocok dengan filter.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal pengingat terkirim (desain 35) -->
<!-- Modal detail tagihan -->
<div class="modal" id="modal-tagihan" role="dialog" aria-modal="true" aria-labelledby="tagihan-title" hidden>
    <div class="modal-dialog modal-md">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="tagihan-title">Detail Tagihan</h2>
                <p class="modal-subtitle" data-f="subtitle"></p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <div class="detail-list">
            <div class="detail-row"><span class="detail-label">Tipe Kamar</span><span class="detail-value" data-f="type"></span></div>
            <div class="detail-row"><span class="detail-label">Nominal Tagihan</span><span class="detail-value" data-f="amount"></span></div>
            <div class="detail-row"><span class="detail-label">Jatuh Tempo</span><span class="detail-value" data-f="due"></span></div>
            <div class="detail-row"><span class="detail-label">Tanggal Bayar</span><span class="detail-value" data-f="paid"></span></div>
            <div class="detail-row"><span class="detail-label">Metode</span><span class="detail-value">Transfer Bank BCA</span></div>
            <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value" data-f="status"></span></div>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary btn-lg" data-modal-close>Tutup</button>
            <a href="verifikasi-pembayaran.php" class="btn btn-primary btn-lg">Lihat Verifikasi</a>
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
<script src="../assets/js/admin/script.js"></script>
<script src="../assets/js/common/sesi.js"></script>
</body>
</html>
