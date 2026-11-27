<?php
// Kerangka panel ADMIN (tampilan tim: sidebar + topbar). Hanya untuk role pengelola.
// Halaman pemanggil mengatur $judul_halaman. Logout hanya ada SATU: sidebar kiri bawah (dengan dialog konfirmasi).
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/notifikasi.php';
require_once __DIR__ . '/admin_ui.php';

if (($_SESSION['role'] ?? '') !== 'pengelola') {
    http_response_code(403);
    exit('Akses ditolak: halaman ini khusus pengelola.');
}
$judul_halaman = $judul_halaman ?? 'Admin';
$notifBaru = jumlah_notifikasi_baru(ambil_notifikasi($pdo, 'pengelola', (int) $_SESSION['user_id']));

// [label, href dari root, potongan path untuk status aktif, ikon, (opsional) filter status]
$menu = [
    ['Dashboard', 'admin/index.php', 'admin/index.php', 'layout-grid'],
    ['Data Reservasi', 'admin/pemesanan/list.php', 'admin/pemesanan/', 'calendar-check'],
    ['Data Penghuni', 'admin/penghuni/list.php', 'admin/penghuni/', 'users'],
    ['Monitor Kamar', 'admin/kamar/monitor.php', 'admin/kamar/monitor.php', 'bed-double'],
    ['Verifikasi Pembayaran', 'admin/pembayaran/list.php?status=menunggu', 'admin/pembayaran/', 'credit-card', 'menunggu'],
    ['Tagihan & Riwayat', 'admin/pembayaran/list.php', 'admin/pembayaran/', 'receipt-text', ''],
    ['Laporan Kerusakan', 'admin/laporan/list.php', 'admin/laporan/', 'wrench'],
    ['Laporan Keuangan', 'admin/keuangan/index.php', 'admin/keuangan/', 'chart-column'],
    ['Kebijakan & Aturan Kos', 'admin/aturan/list.php', 'admin/aturan/', 'file-text'],
    ['Kelola Kamar', 'admin/kamar/list.php', 'admin/kamar/', 'door-open'],
    ['Fasilitas', 'admin/fasilitas/list.php', 'admin/fasilitas/', 'book-open'],
    ['Pengeluaran', 'admin/pengeluaran/list.php', 'admin/pengeluaran/', 'wallet'],
];
$skrip = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$aktifIdx = null;
foreach ($menu as $i => $m) {
    if (strpos($skrip, $m[2]) === false) {
        continue;
    }
    if (isset($m[4]) && ($_GET['status'] ?? '') !== $m[4] && !($m[4] === '' && ($_GET['status'] ?? '') !== 'menunggu')) {
        continue;
    }
    // "admin/kamar/" (Kelola) tidak boleh ikut aktif di Monitor Kamar
    if ($m[2] === 'admin/kamar/' && strpos($skrip, 'admin/kamar/monitor.php') !== false) {
        continue;
    }
    $aktifIdx = $i;
    break;
}
if (strpos($skrip, 'notifikasi/') !== false) {
    $aktifIdx = null;
}
$namaTampil = $_SESSION['nama'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($judul_halaman) ?> · SIPENKOS Admin</title>
    <link rel="icon" href="<?= $base ?>assets/images/admin/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="<?= $base ?>assets/css/admin/style.css">
    <link rel="stylesheet" href="<?= $base ?>assets/css/admin/responsive.css">
    <link rel="stylesheet" href="<?= $base ?>assets/css/admin/integrasi.css">
</head>
<body data-base="<?= e($base) ?>">
<div class="app">
    <aside class="sidebar" id="sidebar" aria-label="Navigasi admin">
        <nav class="sidebar-nav">
            <?php foreach ($menu as $i => $m): ?>
            <a href="<?= e($base . $m[1]) ?>" class="nav-link<?= $i === $aktifIdx ? ' is-active' : '' ?>"<?= $i === $aktifIdx ? ' aria-current="page"' : '' ?>>
                <?= a_ikon($m[3]) ?><span><?= e($m[0]) ?></span>
            </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <button type="button" class="nav-link nav-link-danger" data-modal-open="modal-logout">
                <?= a_ikon('log-out') ?><span>Keluar</span>
            </button>
        </div>
    </aside>
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="app-body">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="icon-btn sidebar-toggle" data-sidebar-toggle aria-label="Buka menu"><?= a_ikon('menu') ?></button>
                <a href="<?= e($base) ?>admin/index.php" class="logo" aria-label="SIPENKOS - Dashboard">
                    <span>SIPENK</span><svg class="logo-house" viewBox="0 0 18 16" aria-hidden="true"><path d="M2 7.2 9 1.6l7 5.6V14.4H2Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/><path d="M7 14.4V9.6h4v4.8" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg><span>S</span>
                </a>
                <span class="topbar-divider topbar-divider-sm"></span>
                <span class="topbar-kos">Kos Bu Nining</span>
            </div>
            <div class="topbar-right">
                <a href="<?= e($base) ?>notifikasi/list.php" class="icon-btn notif-btn" aria-label="Notifikasi">
                    <?= a_ikon('bell') ?><?php if ($notifBaru > 0): ?><span class="notif-dot"></span><?php endif; ?>
                </a>
                <span class="topbar-divider"></span>
                <div class="profile-btn profile-static">
                    <span class="profile-avatar"><?= e(inisial($namaTampil)) ?></span>
                    <span class="profile-name"><?= e($namaTampil) ?></span>
                </div>
            </div>
        </header>
        <main class="main" id="main">
            <?php a_flash(); ?>
