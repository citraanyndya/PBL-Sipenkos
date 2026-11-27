<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/pemesanan.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('list.php');
}
csrf_verify();

$id = (int) ($_POST['id'] ?? 0);
try {
    pemesanan_tolak($pdo, $id, (int) $_SESSION['user_id'], trim($_POST['alasan_penolakan'] ?? ''));
    set_flash('sukses', 'Pemesanan ditolak, kamar dilepas kembali.');
} catch (Exception $e) {
    set_flash('error', $e->getMessage());
}
redirect(($_POST['kembali'] ?? '') === 'list' ? 'list.php' : 'detail.php?id=' . $id);
