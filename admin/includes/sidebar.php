<?php
$activeMenu = $activeMenu ?? '';
$menu = [
    'dashboard'  => ['Dashboard', 'layout-grid', 'dashboard.php'],
    'reservasi'  => ['Data Reservasi', 'calendar-check', 'data-reservasi.php'],
    'penghuni'   => ['Data Penghuni', 'users', 'data-penghuni.php'],
    'kamar'      => ['Monitor Kamar', 'bed-double', 'monitor-kamar.php'],
    'verifikasi' => ['Verifikasi Pembayaran', 'credit-card', 'verifikasi-pembayaran.php'],
    'tagihan'    => ['Tagihan &amp; Riwayat', 'receipt-text', 'tagihan-riwayat.php'],
    'kerusakan'  => ['Laporan Kerusakan', 'wrench', 'laporan-kerusakan.php'],
    'keuangan'   => ['Laporan Keuangan', 'chart-column', 'laporan-keuangan.php'],
    'kebijakan'  => ['Kebijakan &amp; Aturan Kos', 'file-text', 'aturan-kos.php'],
];
?>
<aside class="sidebar" id="sidebar" aria-label="Navigasi admin">
    <nav class="sidebar-nav">
        <?php foreach ($menu as $key => $item) { ?>
            <a href="<?php echo $item[2]; ?>" class="nav-link<?php if ($key == $activeMenu) { echo ' is-active'; } ?>"<?php if ($key == $activeMenu) { echo ' aria-current="page"'; } ?>>
                <i class="icon icon-<?php echo $item[1]; ?>" aria-hidden="true"></i>
                <span><?php echo $item[0]; ?></span>
            </a>
        <?php } ?>
    </nav>
    <div class="sidebar-footer">
        <button type="button" class="nav-link nav-link-danger" data-modal-open="modal-logout">
            <i class="icon icon-log-out" aria-hidden="true"></i>
            <span>Keluar</span>
        </button>
    </div>
</aside>
