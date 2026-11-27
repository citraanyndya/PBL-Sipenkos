<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/pemesanan.php';
$config = require __DIR__ . '/../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('list.php');
}
csrf_verify();

$idPembayaran = (int) ($_POST['id_pembayaran'] ?? 0);
$idPemesanan = (int) ($_POST['id_pemesanan'] ?? 0);
$alasan = trim($_POST['alasan_penolakan'] ?? '');

if ($alasan === '') {
    set_flash('error', 'Alasan penolakan wajib diisi.');
    redirect('../pemesanan/detail.php?id=' . $idPemesanan);
}
try {
    pembayaran_tolak($pdo, $idPembayaran, (int) $_SESSION['user_id'], $alasan, $config['hold_jam']);
    set_flash('sukses', 'Bukti ditolak. Penghuni dapat mengunggah ulang bukti transfer.');
} catch (Exception $e) {
    set_flash('error', $e->getMessage());
}
redirect('../pemesanan/detail.php?id=' . $idPemesanan);
