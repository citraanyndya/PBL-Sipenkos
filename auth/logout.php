<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$_SESSION = [];
session_destroy();
// Mulai session baru hanya untuk menampilkan pesan keluar.
session_start();
set_flash('sukses', 'Kamu sudah keluar.');
redirect('../calon-penghuni/login.php');
