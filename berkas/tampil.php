<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/upload.php';

function tolak($kode, $pesan)
{
    http_response_code($kode);
    die($pesan);
}

$role = $_SESSION['role'] ?? null;
if ($role === null) {
    tolak(401, 'Silakan login.');
}

$jenis = $_GET['jenis'] ?? '';
$nama = basename($_GET['f'] ?? '');
$folder = ['ktp' => 'ktp', 'bukti' => 'bukti', 'kerusakan' => 'kerusakan'][$jenis] ?? null;
if ($folder === null || $nama === '') {
    tolak(404, 'Berkas tidak ditemukan.');
}

if ($role !== 'pengelola') {
    $milik = [
        'ktp' => "SELECT 1 FROM penghuni WHERE id_penghuni = :u AND foto_ktp = :f",
        'bukti' => "SELECT 1 FROM pembayaran b JOIN pemesanan p ON p.id_pemesanan = b.id_pemesanan
                     WHERE p.id_penghuni = :u AND b.bukti_transfer = :f",
        'kerusakan' => "SELECT 1 FROM laporan_kerusakan WHERE id_penghuni = :u AND foto_kerusakan = :f",
    ];
    $stmt = $pdo->prepare($milik[$jenis]);
    $stmt->execute(['u' => $_SESSION['user_id'], 'f' => $nama]);
    if (!$stmt->fetch()) {
        tolak(403, 'Akses ditolak.');
    }
}

$path = upload_dir($folder) . '/' . $nama;
if (!is_file($path)) {
    tolak(404, 'Berkas tidak ditemukan.');
}
header('Content-Type: ' . (new finfo(FILEINFO_MIME_TYPE))->file($path));
header('X-Content-Type-Options: nosniff');
header('Content-Disposition: inline');
header('Cache-Control: private, no-store, max-age=0');   
header('Content-Length: ' . filesize($path));
readfile($path);
