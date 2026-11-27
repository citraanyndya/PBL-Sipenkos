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

$id = input_int($_POST, 'id') ?? 0;
if ($id < 1) {
    redirect('list.php');
}

[$d, $err] = validasi_fasilitas($_POST);
if ($err) {
    set_flash('error', $err);
    $_SESSION['old'] = $d;
    redirect('edit.php?id=' . $id);
}

$pdo->prepare("UPDATE fasilitas SET nama_fasilitas = :nama_fasilitas, kategori_fasilitas = :kategori_fasilitas WHERE id_fasilitas = :id")->execute([
    'nama_fasilitas' => $d['nama_fasilitas'],
    'kategori_fasilitas' => $d['kategori_fasilitas'],
    'id' => $id,
]);

set_flash('sukses', 'Fasilitas berhasil diperbarui.');
redirect('list.php');
