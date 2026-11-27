<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/validasi.php';
$judul_halaman = "Pengeluaran";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('list.php');
}
csrf_verify();

[$d, $err] = validasi_pengeluaran($_POST);
if ($err) {
    set_flash('error', $err);
    $_SESSION['old'] = $d;
    redirect('tambah.php');
}

$pdo->prepare("INSERT INTO pengeluaran_operasional (tgl_pengeluaran, kategori, deskripsi_pengeluaran, jml_biaya, id_pengelola) VALUES (:tgl_pengeluaran, :kategori, :deskripsi_pengeluaran, :jml_biaya, :adm)")->execute([
    'tgl_pengeluaran' => $d['tgl_pengeluaran'],
    'kategori' => $d['kategori'],
    'deskripsi_pengeluaran' => $d['deskripsi_pengeluaran'],
    'jml_biaya' => $d['jml_biaya'],
    'adm' => $_SESSION['user_id'],
]);

set_flash('sukses', 'Pengeluaran berhasil ditambahkan.');
redirect('list.php');
