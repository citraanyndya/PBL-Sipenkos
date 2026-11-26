<?php
// Koneksi PostgreSQL via PDO_PGSQL.
// Sesuaikan dengan PostgreSQL di komputermu (Laragon: port bawaan 5432, user postgres).
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '5432';
$db   = getenv('DB_NAME') ?: 'demo kos bu nining';
$user = getenv('DB_USER') ?: 'postgres';
$pass = getenv('DB_PASS') ?: '';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname='$db'", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("SET TIME ZONE 'Asia/Jakarta'");
} catch (PDOException $e) {
    // Detail teknis hanya ke log server; pengunjung tidak boleh melihat host/user database.
    error_log('SIPENKOS koneksi database gagal: ' . $e->getMessage());
    http_response_code(500);
    exit('Layanan sedang tidak tersedia. Periksa pengaturan di includes/koneksi.php (lihat log error PHP untuk detailnya).');
}

// Lepas hold kamar yang sudah habis (PHP tidak punya proses latar belakang, jadi dicek
// setiap request yang membuka database). Diabaikan bila tabel belum dibuat.
require_once __DIR__ . '/hold.php';
try {
    lepas_hold_kedaluwarsa($pdo);
} catch (Throwable $e) {
    // belum ada tabel pemesanan/kamar
}
