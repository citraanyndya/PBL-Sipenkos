<?php
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
$judul_halaman = "Monitor Kamar";

$tipe = $pdo->query(
    "SELECT kelas_kamar, MIN(harga_sewa) AS harga, COUNT(*) AS total,
            COUNT(*) FILTER (WHERE status_kamar = 'terisi') AS terisi,
            COUNT(*) FILTER (WHERE status_kamar = 'ditahan') AS ditahan,
            COUNT(*) FILTER (WHERE status_kamar = 'tersedia') AS kosong
       FROM kamar GROUP BY kelas_kamar ORDER BY MIN(harga_sewa)"
)->fetchAll();
$total = array_sum(array_column($tipe, 'total'));
$terisi = array_sum(array_column($tipe, 'terisi'));
$kosong = array_sum(array_column($tipe, 'kosong'));
$ditahan = array_sum(array_column($tipe, 'ditahan'));
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Monitor Kamar</h1>
        <p class="page-subtitle">Pantau ketersediaan dan tingkat okupansi kamar kos</p>
    </div>
</div>
<section class="stats stats-4" aria-label="Ringkasan kamar">
    <div class="card stat stat-row"><span class="icon-box icon-box-lg tone-blue"><?= a_ikon('bed-double') ?></span><div><p class="stat-label">Total Kamar</p><p class="stat-value"><?= $total ?></p></div></div>
    <div class="card stat stat-row"><span class="icon-box icon-box-lg tone-green"><?= a_ikon('users') ?></span><div><p class="stat-label">Kamar Terisi</p><p class="stat-value"><?= $terisi ?></p></div></div>
    <div class="card stat stat-row"><span class="icon-box icon-box-lg tone-gray"><?= a_ikon('clock') ?></span><div><p class="stat-label">Kamar Ditahan</p><p class="stat-value"><?= $ditahan ?></p></div></div>
    <div class="card stat stat-row"><span class="icon-box icon-box-lg tone-amber"><?= a_ikon('door-open') ?></span><div><p class="stat-label">Kamar Kosong</p><p class="stat-value"><?= $kosong ?></p></div></div>
</section>
<div class="card">
    <div class="card-head card-head-bar">
        <h2 class="card-title">Ketersediaan per Tipe Kamar</h2>
        <a class="btn btn-primary btn-sm" href="list.php">Kelola Kamar</a>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Tipe Kamar</th><th>Harga / Bulan</th><th>Total</th><th>Terisi</th><th>Ditahan</th><th>Kosong</th><th>Status</th><th class="ta-right">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($tipe as $t): ?>
                <tr>
                    <td class="cell-wrap"><?= e($t['kelas_kamar']) ?></td>
                    <td class="nowrap"><?= rupiah($t['harga']) ?></td>
                    <td><?= (int) $t['total'] ?></td>
                    <td><?= (int) $t['terisi'] ?></td>
                    <td><?= (int) $t['ditahan'] ?></td>
                    <td><?= (int) $t['kosong'] ?></td>
                    <td><?= $t['kosong'] > 0 ? a_badge('tersedia', 'Tersedia · sisa ' . (int) $t['kosong']) : a_badge('ditolak', 'Penuh') ?></td>
                    <td><div class="row-actions"><a class="btn btn-secondary btn-sm" href="list.php?q=<?= urlencode($t['kelas_kamar']) ?>">Kelola</a></div></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
