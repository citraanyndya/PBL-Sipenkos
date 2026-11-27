<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/validasi.php';
require_once __DIR__ . '/../../includes/upload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('list.php');
}
csrf_verify();

$id = input_int($_POST, 'id') ?? 0;
if ($id < 1) {
    redirect('list.php');
}
[$d, $err] = validasi_kamar($_POST);
if ($err) {
    set_flash('error', $err);
    redirect('edit.php?id=' . $id);
}

$fotoBaru = null;
try {
    $fotoBaru = simpan_upload('foto_kamar', 'kamar', false);
    $pdo->beginTransaction();

    $lama = $pdo->prepare("SELECT foto_kamar, harga_sewa FROM kamar WHERE id_kamar = :id FOR UPDATE");
    $lama->execute(['id' => $id]);
    $kamarLama = $lama->fetch();
    $fotoLama = $kamarLama ? $kamarLama['foto_kamar'] : false;
    if ($fotoLama === false) {
        throw new Exception('Kamar tidak ditemukan.');
    }

    $pdo->prepare(
        "UPDATE kamar SET nomor_kamar = :n, kelas_kamar = :k, harga_sewa = :h, status_kamar = :s, foto_kamar = :f WHERE id_kamar = :id"
    )->execute(['n' => $d['nomor_kamar'], 'k' => $d['kelas_kamar'], 'h' => $d['harga_sewa'], 's' => $d['status_kamar'], 'f' => $fotoBaru ?: $fotoLama, 'id' => $id]);

    // Jejak audit: harga kamar berubah -> catat. Pemesanan yang sudah ada TIDAK ikut berubah (harga_sewa_saat_pesan).
    if (abs((float) $kamarLama['harga_sewa'] - (float) $d['harga_sewa']) > 0.004) {
        $pdo->prepare("INSERT INTO riwayat_harga_kamar (id_kamar, harga_lama, harga_baru, id_pengelola) VALUES (:k, :lama, :baru, :adm)")
            ->execute(['k' => $id, 'lama' => $kamarLama['harga_sewa'], 'baru' => $d['harga_sewa'], 'adm' => $_SESSION['user_id']]);
    }
    $pdo->prepare("DELETE FROM kamar_fasilitas WHERE id_kamar = :id")->execute(['id' => $id]);
    $ins = $pdo->prepare("INSERT INTO kamar_fasilitas (id_kamar, id_fasilitas) VALUES (:k, :f)");
    foreach ($d['fasilitas'] as $f) {
        $ins->execute(['k' => $id, 'f' => $f]);
    }
    $pdo->commit();
    if ($fotoBaru) {
        hapus_upload('kamar', $fotoLama);
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    hapus_upload('kamar', $fotoBaru);
    $pesan = $e instanceof PDOException ? ($e->getCode() === '23505' ? 'Nomor kamar sudah dipakai.' : 'Fasilitas atau data tidak valid.') : $e->getMessage();
    set_flash('error', $pesan);
    redirect('edit.php?id=' . $id);
}

set_flash('sukses', 'Kamar berhasil diperbarui.');
redirect('list.php');
