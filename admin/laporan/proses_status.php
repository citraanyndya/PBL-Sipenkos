<?php
// Fitur 09 - Laporan Kerusakan: pengelola mengubah status baru -> diproses -> selesai.
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('list.php');
}
csrf_verify();

$id = (int) ($_POST['id'] ?? 0);
$status = input_str($_POST, 'status', 20);

// Status hanya boleh maju satu langkah: baru -> diproses -> selesai.
$sebelum = ['diproses' => 'baru', 'selesai' => 'diproses'];
if (!isset($sebelum[$status])) {
    set_flash('error', 'Status tidak dikenal.');
    redirect('list.php');
}

$stmt = $pdo->prepare(
    "UPDATE laporan_kerusakan SET status_laporan = :baru, id_pengelola = :p
      WHERE id_laporan = :i AND status_laporan = :lama"
);
$stmt->execute([
    'baru' => $status,
    'p' => (int) $_SESSION['user_id'],
    'i' => $id,
    'lama' => $sebelum[$status],
]);

if ($stmt->rowCount() === 0) {
    set_flash('error', 'Status laporan sudah berubah, muat ulang halaman.');
} else {
    set_flash('sukses', $status === 'selesai' ? 'Laporan ditandai selesai.' : 'Laporan ditandai sedang diproses.');
}
redirect(($_POST['kembali'] ?? '') === 'list' ? 'list.php' : 'detail.php?id=' . $id);
