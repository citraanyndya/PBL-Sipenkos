<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/validasi.php';
require_once __DIR__ . '/../../includes/upload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('tambah.php');
}
csrf_verify();

[$d, $err] = validasi_kamar($_POST);
if ($d['status_kamar'] === 'ditahan') {
    $err[] = 'Status "ditahan" hanya diatur otomatis oleh sistem saat ada reservasi.';
}
if ($err) {
    set_flash('error', $err);
    redirect('tambah.php');
}

$foto = null;
try {
    $foto = simpan_upload('foto_kamar', 'kamar', false);
    $pdo->beginTransaction();
    $stmt = $pdo->prepare(
        "INSERT INTO kamar (nomor_kamar, kelas_kamar, harga_sewa, status_kamar, foto_kamar)
         VALUES (:n, :k, :h, :s, :f) RETURNING id_kamar"
    );
    $stmt->execute(['n' => $d['nomor_kamar'], 'k' => $d['kelas_kamar'], 'h' => $d['harga_sewa'], 's' => $d['status_kamar'], 'f' => $foto]);
    $idKamar = $stmt->fetchColumn();

    $ins = $pdo->prepare("INSERT INTO kamar_fasilitas (id_kamar, id_fasilitas) VALUES (:k, :f)");
    foreach ($d['fasilitas'] as $f) {
        $ins->execute(['k' => $idKamar, 'f' => $f]);
    }
    $pdo->commit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    hapus_upload('kamar', $foto);
    $pesan = $e instanceof PDOException ? ($e->getCode() === '23505' ? 'Nomor kamar sudah dipakai.' : 'Fasilitas atau data tidak valid.') : $e->getMessage();
    set_flash('error', $pesan);
    redirect('tambah.php');
}

set_flash('sukses', 'Kamar berhasil ditambahkan.');
redirect('list.php');
