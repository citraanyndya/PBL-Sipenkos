<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/validasi.php';
$judul_halaman = "Fasilitas";

// Sengaja hanya menerima POST agar penghapusan tidak terpicu lewat link/crawler.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('list.php');
}
csrf_verify();

$id = input_int($_POST, 'id') ?? 0;
if ($id > 0) {
    try {
        $pdo->prepare("DELETE FROM fasilitas WHERE id_fasilitas = :id")->execute(['id' => $id]);
        set_flash('sukses', 'Fasilitas berhasil dihapus.');
    } catch (PDOException $e) {
        set_flash('error', 'Data tidak dapat dihapus karena masih dipakai data lain.');
    }
}
redirect('list.php');
