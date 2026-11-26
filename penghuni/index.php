<?php
// Gerbang penghuni (state-based): sewa aktif -> dasbor; belum aktif -> status reservasi / beranda.
$base = '../';
require_once __DIR__ . '/../includes/auth.php';
wajib_role('penghuni');
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fe.php';
if (fe_penghuni_aktif($pdo)) {
    redirect('dashboard.php');
}
$ada = $pdo->prepare('SELECT 1 FROM pemesanan WHERE id_penghuni = :u LIMIT 1');
$ada->execute(['u' => $_SESSION['user_id']]);
redirect($ada->fetch() ? '../calon-penghuni/status-reservasi.php' : '../calon-penghuni/beranda.php');
