<?php
$page_title = $page_title ?? 'Kebijakan &amp; Aturan Kos';
$page_id = $page_id ?? 'aturan-kos';
$page_module = 'kebijakan';
$activeMenu = 'kebijakan';
include __DIR__ . '/../includes/header.php';

$mode = $mode ?? 'view';

$aturan = [
    ['title' => 'Jam Bertamu', 'text' => 'Bertamu maksimal hingga pukul 22.00 WIB di area ruang tamu bersama.'],
    ['title' => 'Keamanan & Kunci', 'text' => 'Pintu pagar utama wajib dikunci kembali setelah pukul 23.00 WIB.'],
    ['title' => 'Pembayaran Sewa', 'text' => 'Pembayaran sewa dilakukan paling lambat tanggal 27 setiap bulannya.'],
    ['title' => 'Kebersihan & Ketertiban', 'text' => 'Dilarang membuat kegaduhan di atas pukul 22.00 WIB dan wajib menjaga kebersihan area bersama.'],
    ['title' => 'Penggunaan Fasilitas', 'text' => 'Penggunaan fasilitas umum (dapur/mesin cuci) harap dirapikan kembali setelah digunakan.'],
];
$terakhirDiperbarui = '10 Okt 2026';

$aturan_baru = null;
if ($mode != 'view') {
    $aturan_baru = ['title' => 'Hewan Peliharaan', 'text' => 'Dilarang membawa atau memelihara hewan peliharaan di area kos tanpa izin dari pengelola.'];
}

$daftar = $aturan;
if ($mode == 'saved') {
    $daftar[] = $aturan_baru;
}
$jumlahAturan = ($mode == 'view') ? count($aturan) : count($aturan) + 1;

$app_data = ['rules' => $aturan, 'ruleDraft' => $aturan_baru, 'rulesUpdated' => $terakhirDiperbarui];
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Kebijakan &amp; Aturan Kos</h1>
        <p class="page-subtitle">Atur pedoman dan tata tertib kos yang berlaku untuk seluruh penghuni</p>
    </div>
    <div class="page-actions" data-actions-view<?php if ($mode == 'edit') { echo ' hidden'; } ?>>
        <button type="button" class="btn btn-secondary btn-sm" data-rules-edit><i class="icon icon-pencil" aria-hidden="true"></i>Edit Aturan</button>
    </div>
    <div class="page-actions" data-actions-edit<?php if ($mode != 'edit') { echo ' hidden'; } ?>>
        <button type="button" class="btn btn-secondary btn-sm" data-rules-cancel>Batal</button>
        <button type="button" class="btn btn-primary btn-sm" data-rules-save>Simpan Perubahan</button>
    </div>
</div>
<div data-banner-slot>
    <?php if ($mode == 'saved') { ?>
        <div class="banner banner-success banner-tight"><div class="banner-body"><i class="icon icon-circle-check" aria-hidden="true"></i><span>Perubahan kebijakan berhasil disimpan dan sudah tampil untuk penghuni</span></div></div>
    <?php } elseif ($mode == 'edit') { ?>
        <div class="banner banner-warning banner-tight"><div class="banner-body"><i class="icon icon-triangle-alert" aria-hidden="true"></i><span>Mode edit aktif · perubahan baru berlaku setelah kamu klik Simpan Perubahan</span></div></div>
    <?php } else { ?>
        <div class="banner banner-info banner-tight"><div class="banner-body"><i class="icon icon-info" aria-hidden="true"></i><span>Aturan ini tampil di halaman Tentang dan halaman Kebijakan penghuni · Terakhir diperbarui <?php echo $terakhirDiperbarui; ?></span></div></div>
    <?php } ?>
</div>
<div class="card rules-card" id="rules" data-mode="<?php echo $mode; ?>">
    <div class="rules-head">
        <h2 class="rules-head-title"><i class="icon icon-book-open" aria-hidden="true"></i>Tata Tertib Kos Bu Nining</h2>
        <span class="rules-count" data-rule-count><?php echo $jumlahAturan; ?> aturan</span>
    </div>
    <div class="rules-list" id="rules-view"<?php if ($mode == 'edit') { echo ' hidden'; } ?>>
        <?php foreach ($daftar as $i => $a) { ?>
            <div class="rule">
                <span class="rule-num"><?php echo $i + 1; ?></span>
                <div class="rule-body"><p class="rule-title"><?php echo htmlspecialchars($a['title']); ?></p><p class="rule-text"><?php echo $a['text']; ?></p></div>
            </div>
        <?php } ?>
    </div>
    <div class="rules-list" id="rules-edit"<?php if ($mode != 'edit') { echo ' hidden'; } ?>>
        <div class="rules-rows" data-rule-rows></div>
        <button type="button" class="rule-add" data-rule-add>+ Tambah Aturan Baru</button>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
