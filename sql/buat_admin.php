<?php
// Skrip CLI: membuat/menambah akun PENGELOLA dengan password ter-hash.
// Pemakaian:  php sql/buat_admin.php                          -> admin / admin123
//             php sql/buat_admin.php "Nama" username password  -> akun baru
if (PHP_SAPI !== 'cli') {
    exit('Skrip ini hanya boleh dijalankan lewat command line.');
}
require_once __DIR__ . '/../includes/koneksi.php';

$akun = ($argc >= 4)
    ? [[$argv[1], $argv[2], $argv[3]]]
    : [['Admin Kos Bu Nining', 'admin', 'admin123']];

$stmt = $pdo->prepare(
    "INSERT INTO pengelola (nama_pengelola, username_pengelola, password_pengelola, peran)
     VALUES (:n, :u, :p, 'pemilik') ON CONFLICT (username_pengelola) DO NOTHING"
);
foreach ($akun as [$nama, $user, $pass]) {
    $stmt->execute([':n' => $nama, ':u' => $user, ':p' => password_hash($pass, PASSWORD_DEFAULT)]);
    echo ($stmt->rowCount() ? "Dibuat   : " : "Sudah ada: ") . "$user\n";
}
