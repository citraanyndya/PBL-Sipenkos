<?php
// Fitur 09 - Laporan Kerusakan: menerima laporan baru dari penghuni.
require_once __DIR__ . '/../includes/auth.php';
wajib_role('penghuni');
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/validasi.php';
require_once __DIR__ . '/../includes/upload.php';
require_once __DIR__ . '/../includes/fe.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../penghuni/laporan-kerusakan.php');
}
csrf_verify();

// Kamar yang sedang disewa; bila sewa tidak aktif, fe_wajib_aktif() menolak dan mengalihkan.
$sewa = fe_wajib_aktif($pdo);

$kategori = input_str($_POST, 'kategori', 40);
if (!in_array($kategori, KATEGORI_LAPORAN, true) ) {
    $kategori = 'Lainnya';
}
$judul = input_str($_POST, 'judul', 80);
[$d, $err] = validasi_laporan(['deskripsi_laporan' => input_str($_POST, 'deskripsi', 800)]);
if ($judul === '') {
    array_unshift($err, 'Judul kerusakan wajib diisi.');
}
if ($err) {
    set_flash('error', $err);
    $_SESSION['old'] = ['kategori' => $kategori, 'judul' => $judul, 'deskripsi' => $d['deskripsi_laporan']];
    redirect('../penghuni/buat-laporan-kerusakan.php');
}

// Tabel laporan_kerusakan hanya punya satu kolom teks. Kategori, judul, dan isi
// digabung memakai format yang dibaca fe_pecah_laporan(): "[Kategori] Judul\nIsi".
$deskripsi = '[' . $kategori . '] ' . $judul . "\n" . $d['deskripsi_laporan'];

try {
    $foto = simpan_upload('foto', 'kerusakan', false);   // opsional
} catch (Exception $e) {
    set_flash('error', $e->getMessage());
    $_SESSION['old'] = ['kategori' => $kategori, 'judul' => $judul, 'deskripsi' => $d['deskripsi_laporan']];
    redirect('../penghuni/buat-laporan-kerusakan.php');
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO laporan_kerusakan (deskripsi_laporan, foto_kerusakan, status_laporan, id_penghuni, id_kamar)
         VALUES (:d, :f, 'baru', :u, :k) RETURNING id_laporan"
    );
    $stmt->execute([
        'd' => $deskripsi,
        'f' => $foto,
        'u' => (int) $_SESSION['user_id'],
        'k' => (int) $sewa['id_kamar'],
    ]);
    $id = (int) $stmt->fetchColumn();
} catch (PDOException $e) {
    hapus_upload('kerusakan', $foto);   // jangan tinggalkan file yatim
    set_flash('error', 'Laporan gagal disimpan, coba lagi.');
    redirect('../penghuni/buat-laporan-kerusakan.php');
}

set_flash('sukses', 'Laporan terkirim. Pemilik kos akan segera menindaklanjuti.');
redirect('../penghuni/detail-laporan-kerusakan.php?id=' . $id);
