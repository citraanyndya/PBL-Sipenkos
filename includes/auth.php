<?php

require_once __DIR__ . '/bootstrap.php';

if (empty($_SESSION['user_id'])) {
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'GET') {
        $_SESSION['setelah_login'] = $_SERVER['REQUEST_URI'];
    }
    if (empty($_SESSION['flash'])) {   // jangan menimpa pesan lain, mis. "Sesi berakhir"
        set_flash('error', 'Silakan login terlebih dahulu.');
    }
    header('Location: ' . $base . 'calon-penghuni/login.php');
    exit;
}

function wajib_role(string ...$role): void
{
    if (!in_array($_SESSION['role'] ?? '', $role, true)) {
        http_response_code(403);
        exit('Akses ditolak: akun Anda tidak diizinkan membuka halaman ini.');
    }
}
