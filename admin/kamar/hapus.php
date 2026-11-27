<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/upload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('list.php');
}
csrf_verify();

$id = (int) ($_POST['id'] ?? 0);
if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM kamar WHERE id_kamar = :id RETURNING foto_kamar");
        $stmt->execute(['id' => $id]);
        $foto = $stmt->fetchColumn();
        hapus_upload('kamar', $foto ?: null);
        set_flash('sukses', 'Kamar berhasil dihapus.');
    } catch (PDOException $e) {
        set_flash('error', 'Kamar tidak dapat dihapus karena sudah punya riwayat pemesanan/laporan.');
    }
}
redirect('list.php');
