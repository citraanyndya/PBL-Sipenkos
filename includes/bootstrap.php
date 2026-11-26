<?php

date_default_timezone_set('Asia/Jakarta');
require_once __DIR__ . '/helpers.php';    // memulai session dengan cookie aman
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/konstanta.php';
require_once __DIR__ . '/format.php';

// Sesi berakhir otomatis bila tidak aktif (mengurangi risiko perangkat bersama/terbuka).
if (!empty($_SESSION['user_id'])) {
    $batasIdle = (((require __DIR__ . '/config.php')['sesi_idle_menit']) ?: 120) * 60;
    if (isset($_SESSION['aktif_terakhir']) && time() - $_SESSION['aktif_terakhir'] > $batasIdle) {
        $_SESSION = [];
        session_destroy();
        session_start();
        set_flash('info', 'Sesi berakhir karena tidak aktif. Silakan masuk kembali.');
    } else {
        $_SESSION['aktif_terakhir'] = time();
    }
}

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'");


if (!isset($base)) {
    $base = base_path();
}
