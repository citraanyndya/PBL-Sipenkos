<?php
/**
 * Sidebar admin (komponen Admin/Sidebar di Figma).
 * Halaman mengatur menu aktif lewat variabel $activeMenu sebelum include.
 */
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
        <?php foreach ($menu as $key => [$label, $icon, $href]): ?>
            <a href="<?= $href ?>" class="nav-link<?= $key === $activeMenu ? ' is-active' : '' ?>"<?= $key === $activeMenu ? ' aria-current="page"' : '' ?>>
                <i class="icon icon-<?= $icon ?>" aria-hidden="true"></i>
                <span><?= $label ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-footer">
        <button type="button" class="nav-link nav-link-danger" data-modal-open="modal-logout">
            <i class="icon icon-log-out" aria-hidden="true"></i>
            <span>Keluar</span>
        </button>
    </div>
</aside>
