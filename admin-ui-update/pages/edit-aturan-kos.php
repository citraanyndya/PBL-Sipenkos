<?php /* Edit Kebijakan & Aturan Kos · UI Admin SIPENKOS */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kebijakan &amp; Aturan Kos · SIPENKOS Admin</title>
    <link rel="icon" href="../assets/images/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
</head>
<body data-page="edit-aturan-kos" data-module="kebijakan">
<div class="app">
    <?php $activeMenu = 'kebijakan'; include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="app-body">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
        <main class="main" id="main">
<div class="page-header">
    <div>
        <h1 class="page-title">Kebijakan &amp; Aturan Kos</h1>
        <p class="page-subtitle">Atur pedoman dan tata tertib kos yang berlaku untuk seluruh penghuni</p>
    </div>
    <div class="page-actions" data-actions-view hidden>
        <button type="button" class="btn btn-secondary btn-sm" data-rules-edit><i class="icon icon-pencil" aria-hidden="true"></i>Edit Aturan</button>
    </div>
    <div class="page-actions" data-actions-edit>
        <button type="button" class="btn btn-secondary btn-sm" data-rules-cancel>Batal</button>
        <button type="button" class="btn btn-primary btn-sm" data-rules-save>Simpan Perubahan</button>
    </div>
</div>
<div data-banner-slot>
            <div class="banner banner-warning banner-tight"><div class="banner-body"><i class="icon icon-triangle-alert" aria-hidden="true"></i><span>Mode edit aktif · perubahan baru berlaku setelah kamu klik Simpan Perubahan</span></div></div>
    </div>
<div class="card rules-card" id="rules" data-mode="edit">
    <div class="rules-head">
        <h2 class="rules-head-title"><i class="icon icon-book-open" aria-hidden="true"></i>Tata Tertib Kos Bu Nining</h2>
        <span class="rules-count" data-rule-count>6 aturan</span>
    </div>
    <div class="rules-list" id="rules-view" hidden>
                    <div class="rule">
                <span class="rule-num">1</span>
                <div class="rule-body"><p class="rule-title">Jam Bertamu</p><p class="rule-text">Bertamu maksimal hingga pukul 22.00 WIB di area ruang tamu bersama.</p></div>
            </div>
                    <div class="rule">
                <span class="rule-num">2</span>
                <div class="rule-body"><p class="rule-title">Keamanan &amp; Kunci</p><p class="rule-text">Pintu pagar utama wajib dikunci kembali setelah pukul 23.00 WIB.</p></div>
            </div>
                    <div class="rule">
                <span class="rule-num">3</span>
                <div class="rule-body"><p class="rule-title">Pembayaran Sewa</p><p class="rule-text">Pembayaran sewa dilakukan paling lambat tanggal 27 setiap bulannya.</p></div>
            </div>
                    <div class="rule">
                <span class="rule-num">4</span>
                <div class="rule-body"><p class="rule-title">Kebersihan &amp; Ketertiban</p><p class="rule-text">Dilarang membuat kegaduhan di atas pukul 22.00 WIB dan wajib menjaga kebersihan area bersama.</p></div>
            </div>
                    <div class="rule">
                <span class="rule-num">5</span>
                <div class="rule-body"><p class="rule-title">Penggunaan Fasilitas</p><p class="rule-text">Penggunaan fasilitas umum (dapur/mesin cuci) harap dirapikan kembali setelah digunakan.</p></div>
            </div>
            </div>
    <div class="rules-list" id="rules-edit">
        <div class="rules-rows" data-rule-rows></div>
        <button type="button" class="rule-add" data-rule-add>+ Tambah Aturan Baru</button>
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
<script>window.APP_DATA = {"rules":[{"title":"Jam Bertamu","text":"Bertamu maksimal hingga pukul 22.00 WIB di area ruang tamu bersama."},{"title":"Keamanan \u0026 Kunci","text":"Pintu pagar utama wajib dikunci kembali setelah pukul 23.00 WIB."},{"title":"Pembayaran Sewa","text":"Pembayaran sewa dilakukan paling lambat tanggal 27 setiap bulannya."},{"title":"Kebersihan \u0026 Ketertiban","text":"Dilarang membuat kegaduhan di atas pukul 22.00 WIB dan wajib menjaga kebersihan area bersama."},{"title":"Penggunaan Fasilitas","text":"Penggunaan fasilitas umum (dapur\/mesin cuci) harap dirapikan kembali setelah digunakan."}],"ruleDraft":{"title":"Hewan Peliharaan","text":"Dilarang membawa atau memelihara hewan peliharaan di area kos tanpa izin dari pengelola."},"rulesUpdated":"10 Okt 2026"};</script>
<script src="../js/script.js"></script>
</body>
</html>
