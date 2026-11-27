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

$id = input_int($_POST, 'id') ?? 0;
if ($id < 1) {
    redirect('list.php');
}

[$d, $err] = validasi_pengeluaran($_POST);
if ($err) {
    set_flash('error', $err);
    $_SESSION['old'] = $d;
    redirect('edit.php?id=' . $id);
}

$pdo->prepare("UPDATE pengeluaran_operasional SET tgl_pengeluaran = :tgl_pengeluaran, kategori = :kategori, deskripsi_pengeluaran = :deskripsi_pengeluaran, jml_biaya = :jml_biaya, id_pengelola = :adm WHERE id_pengeluaran = :id")->execute([
    'tgl_pengeluaran' => $d['tgl_pengeluaran'],
    'kategori' => $d['kategori'],
    'deskripsi_pengeluaran' => $d['deskripsi_pengeluaran'],
    'jml_biaya' => $d['jml_biaya'],
    'adm' => $_SESSION['user_id'],
    'id' => $id,
]);

set_flash('sukses', 'Pengeluaran berhasil diperbarui.');
redirect('list.php');
