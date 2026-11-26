<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/validasi.php';
require_once __DIR__ . '/../includes/throttle.php';
$app = require __DIR__ . '/../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../calon-penghuni/registrasi.php');
}
csrf_verify();

// Batasi registrasi massal/otomatis dari satu IP.
$kunciDaftar = throttle_kunci('daftar');
if (throttle_terblokir($pdo, $kunciDaftar, $app['daftar_maks'], 60)) {
    set_flash('error', 'Terlalu banyak pendaftaran dari jaringan ini. Coba lagi dalam 1 jam.');
    redirect('../calon-penghuni/registrasi.php');
}
throttle_catat($pdo, $kunciDaftar);

[$d, $err] = validasi_registrasi($_POST);
$ingat = ['nama_lengkap' => $d['nama_lengkap'], 'no_hp' => $d['no_hp'], 'username' => $d['username']];
if ($err) {
    set_flash('error', $err);
    $_SESSION['old'] = $ingat;
    redirect('../calon-penghuni/registrasi.php');
}

$cek = $pdo->prepare("SELECT 1 FROM penghuni WHERE username_penghuni = :u");
$cek->execute(['u' => $d['username']]);
if ($cek->fetch()) {
    set_flash('error', 'Username sudah dipakai, pilih yang lain.');
    $_SESSION['old'] = $ingat;
    redirect('../calon-penghuni/registrasi.php');
}

try {
    $pdo->prepare(
        "INSERT INTO penghuni (nama_lengkap, no_hp, username_penghuni, password_penghuni)
         VALUES (:nama, :hp, :u, :pw)"
    )->execute(['nama' => $d['nama_lengkap'], 'hp' => $d['no_hp'], 'u' => $d['username'], 'pw' => password_hash($d['password'], PASSWORD_DEFAULT)]);
} catch (PDOException $e) {
    // Dua pendaftaran dengan username sama pada saat bersamaan: ditangkap oleh UNIQUE di database.
    set_flash('error', $e->getCode() === '23505' ? 'Username sudah dipakai, pilih yang lain.' : 'Terjadi kesalahan, coba lagi.');
    $_SESSION['old'] = $ingat;
    redirect('../calon-penghuni/registrasi.php');
}

set_flash('sukses', 'Registrasi berhasil, silakan masuk.');
redirect('../calon-penghuni/login.php');
