<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/throttle.php';
$app = require __DIR__ . '/../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../calon-penghuni/login.php');
}
csrf_verify();

$tipe = input_str($_POST, 'tipe', 10) === 'pengelola' ? 'pengelola' : 'penghuni';   // whitelist
$username = input_str($_POST, 'username', 50);
$password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

// Anti brute-force: batasi kegagalan per (username + IP) dan per IP.
$kunciAkun = throttle_kunci('login', $tipe . '/' . $username);
$kunciIp = throttle_kunci('loginip');
if (throttle_terblokir($pdo, $kunciAkun, $app['login_maks'], 15) || throttle_terblokir($pdo, $kunciIp, $app['login_ip_maks'], 15)) {
    set_flash('error', 'Terlalu banyak percobaan masuk. Coba lagi dalam 15 menit.');
    redirect('../calon-penghuni/login.php' . ($tipe === 'pengelola' ? '#pengelola' : ''));
}

if ($tipe === 'pengelola') {
    $stmt = $pdo->prepare("SELECT id_pengelola AS id, nama_pengelola AS nama, password_pengelola AS hash FROM pengelola WHERE username_pengelola = :u");
} else {
    $stmt = $pdo->prepare("SELECT id_penghuni AS id, nama_lengkap AS nama, password_penghuni AS hash FROM penghuni WHERE username_penghuni = :u");
}
$stmt->execute(['u' => $username]);
$user = $stmt->fetch();

// Selalu jalankan password_verify (hash tiruan bila user tak ada) agar waktu respons tidak
// membedakan username terdaftar/tidak. Pesan error juga generik.
$hash = $user['hash'] ?? '$2y$10$7KHexCSvtPgY7K4HYR6USe2VZG2hivIQwTCDNhELQGVd4n1609BTa';
$cocok = password_verify($password, $hash) && $user;
if (!$cocok) {
    throttle_catat($pdo, $kunciAkun);
    throttle_catat($pdo, $kunciIp);
    set_flash('error', 'Username atau kata sandi salah.');
    redirect('../calon-penghuni/login.php' . ($tipe === 'pengelola' ? '#pengelola' : ''));
}

throttle_reset($pdo, $kunciAkun);
session_regenerate_id(true);   // cegah session fixation
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['nama'] = $user['nama'];
$_SESSION['role'] = $tipe;
$_SESSION['aktif_terakhir'] = time();

if ($tipe === 'pengelola') {
    redirect('../admin/index.php');
}
// Kembali ke halaman yang diminta sebelum login (hanya path lokal, cegah open redirect).
$tujuan = $_SESSION['setelah_login'] ?? '';
unset($_SESSION['setelah_login']);
if ($tujuan !== '' && $tujuan[0] === '/' && !str_starts_with($tujuan, '//') && !str_contains($tujuan, '\\')) {
    redirect($tujuan);
}
redirect('../penghuni/index.php');
