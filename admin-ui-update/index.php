<?php
/**
 * Titik masuk UI Admin SIPENKOS.
 * - Tanpa parameter: langsung menuju Dashboard (halaman 25).
 * - ?keluar=1      : tampilan setelah admin menekan tombol Keluar.
 */
if (!isset($_GET['keluar'])) {
    header('Location: pages/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keluar · SIPENKOS Admin</title>
    <link rel="icon" href="assets/images/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
<div class="auth-screen">
    <div class="card auth-card">
        <span class="logo">
            <span>SIPENK</span><svg class="logo-house" viewBox="0 0 18 16" aria-hidden="true"><path d="M2 7.2 9 1.6l7 5.6V14.4H2Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/><path d="M7 14.4V9.6h4v4.8" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg><span>S</span>
        </span>
        <h1 class="result-title">Kamu sudah keluar</h1>
        <p class="result-text">Sesi admin Kos Bu Nining telah diakhiri. Masuk kembali untuk mengelola kos.</p>
        <div class="modal-actions"><a href="pages/dashboard.php" class="btn btn-primary btn-lg btn-block">Masuk Kembali</a></div>
    </div>
</div>
</body>
</html>
