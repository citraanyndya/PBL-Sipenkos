<?php
// Perpanjangan sewa: pilih durasi dan (opsional) tipe kamar lain. Tipe berbeda = pindah kamar:
// kamar baru ditahan (FOR UPDATE SKIP LOCKED, aman dari rebutan) dan baru terisi setelah pemilik menerima.
require_once __DIR__ . '/../includes/auth.php';
wajib_role('penghuni');
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/validasi.php';
$app = require __DIR__ . '/../includes/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../penghuni/perpanjangan-sewa.php');
}
csrf_verify();
[$d, $err] = validasi_durasi($_POST);
if ($err) {
    set_flash('error', $err);
    redirect('../penghuni/perpanjangan-sewa.php');
}
$durasi = $d['durasi_bulan'];
$kelasTujuan = input_str($_POST, 'kelas', 50);
try {
    $pdo->beginTransaction();
    // Kunci baris penghuni: dua klik/dua tab diproses bergantian.
    $pdo->prepare('SELECT 1 FROM penghuni WHERE id_penghuni = :u FOR UPDATE')->execute(['u' => $_SESSION['user_id']]);
    $menunggu = $pdo->prepare("SELECT 1 FROM pemesanan WHERE id_penghuni = :u AND status_pesanan = 'menunggu_verifikasi'");
    $menunggu->execute(['u' => $_SESSION['user_id']]);
    if ($menunggu->fetch()) {
        throw new Exception('Masih ada pengajuan yang menunggu verifikasi.');
    }
    $stmt = $pdo->prepare(
        "SELECT p.id_pemesanan, p.id_kamar, k.kelas_kamar, k.harga_sewa, " . sql_selesai('p') . " AS tgl_selesai
           FROM pemesanan p JOIN kamar k ON k.id_kamar = p.id_kamar
          WHERE p.id_penghuni = :u AND p.status_pesanan = 'diterima' AND " . sql_selesai('p') . " >= CURRENT_DATE
          ORDER BY " . sql_selesai('p') . " DESC LIMIT 1"
    );
    $stmt->execute(['u' => $_SESSION['user_id']]);
    $sewa = $stmt->fetch();
    if (!$sewa) {
        throw new Exception('Tidak ada masa sewa aktif yang dapat diperpanjang.');
    }

    $pindah = $kelasTujuan !== '' && $kelasTujuan !== $sewa['kelas_kamar'];
    if ($pindah) {
        // Ganti tipe: ambil SATU kamar kosong di tipe tujuan; yang sedang dikunci orang lain dilewati.
        $cari = $pdo->prepare(
            "SELECT id_kamar, harga_sewa FROM kamar WHERE kelas_kamar = :k AND status_kamar = 'tersedia'
              ORDER BY harga_sewa, nomor_kamar LIMIT 1 FOR UPDATE SKIP LOCKED"
        );
        $cari->execute(['k' => $kelasTujuan]);
        $baru = $cari->fetch();
        if (!$baru) {
            throw new Exception('Maaf, tipe kamar yang dipilih sedang penuh atau baru saja diambil pengguna lain.');
        }
        $pdo->prepare("UPDATE kamar SET status_kamar = 'ditahan' WHERE id_kamar = :k")->execute(['k' => $baru['id_kamar']]);
        $kamar = $baru['id_kamar'];
        $harga = $baru['harga_sewa'];
        $hold = $app['hold_jam'];
    } else {
        $kamar = $sewa['id_kamar'];   // kamar sendiri sudah terisi: tidak perlu hold
        $harga = $sewa['harga_sewa'];
        $hold = null;
    }
    // Harga yang dilihat penghuni harus sama dengan harga yang dikunci sekarang.
    $kiriman = $_POST['harga_tampil'] ?? [];
    $kunciKelas = $pindah ? $kelasTujuan : $sewa['kelas_kamar'];
    $hargaTampil = (is_array($kiriman) && isset($kiriman[$kunciKelas])) ? filter_var($kiriman[$kunciKelas], FILTER_VALIDATE_INT) : null;
    pastikan_harga_sama($harga, $hargaTampil === false ? null : $hargaTampil);
    $ins = $pdo->prepare(
        "INSERT INTO pemesanan (tgl_mulai, durasi_bulan, harga_sewa_saat_pesan, total_tagihan, status_pesanan, batas_hold, id_penghuni, id_kamar, id_pemesanan_asal)
         VALUES (:mulai, :durasi, :harga, :total, 'menunggu_verifikasi', CASE WHEN CAST(:hold AS INT) IS NULL THEN NULL ELSE NOW() + (CAST(:hold2 AS INT) * INTERVAL '1 hour') END, :u, :kamar, :asal)
         RETURNING id_pemesanan"
    );
    $ins->execute([
        'mulai' => $sewa['tgl_selesai'], 'durasi' => $durasi, 'harga' => $harga, 'total' => $harga * $durasi, 'hold' => $hold, 'hold2' => $hold,
        'u' => $_SESSION['user_id'], 'kamar' => $kamar, 'asal' => $sewa['id_pemesanan'],
    ]);
    $id = $ins->fetchColumn();
    $pdo->commit();
    set_flash('sukses', $pindah ? 'Pengajuan pindah kamar & perpanjangan dikirim. Kamar baru ditahan untukmu, silakan lakukan pembayaran.' : 'Pengajuan perpanjangan dikirim. Silakan lakukan pembayaran.');
    redirect('../penghuni/status-perpanjangan-sewa.php?id=' . $id);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    set_flash('error', $e instanceof PDOException ? 'Terjadi kesalahan database, coba lagi.' : $e->getMessage());
    redirect('../penghuni/perpanjangan-sewa.php');
}
