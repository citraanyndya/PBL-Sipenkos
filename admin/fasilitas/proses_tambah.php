<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/validasi.php';
$judul_halaman = "Fasilitas";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('list.php');
}
csrf_verify();

[$d, $err] = validasi_fasilitas($_POST);
if ($err) {
    set_flash('error', $err);
    $_SESSION['old'] = $d;
    redirect('tambah.php');
}

$pdo->prepare("INSERT INTO fasilitas (nama_fasilitas, kategori_fasilitas) VALUES (:nama_fasilitas, :kategori_fasilitas)")->execute([
    'nama_fasilitas' => $d['nama_fasilitas'],
    'kategori_fasilitas' => $d['kategori_fasilitas'],
]);

set_flash('sukses', 'Fasilitas berhasil ditambahkan.');
redirect('list.php');
