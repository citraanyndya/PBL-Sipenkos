<?php
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
$judul_halaman = "Daftar Kamar";

$kw = trim($_GET['q'] ?? '');
$stmt = $pdo->prepare(
    "SELECT * FROM kamar WHERE nomor_kamar ILIKE :kw OR kelas_kamar ILIKE :kw ORDER BY nomor_kamar"
);
$stmt->execute(['kw' => '%' . $kw . '%']);
$daftar = $stmt->fetchAll();
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Kamar</h1>
        <p class="page-subtitle">Tambah, ubah, dan hapus data kamar. <a class="link" href="monitor.php">Monitor ketersediaan &rarr;</a></p>
    </div>
    <div class="page-actions"><a class="btn btn-primary btn-md" href="tambah.php">+ Tambah Kamar</a></div>
</div>
<form method="get" action="list.php" class="search-form">
    <label class="search"><?= a_ikon('search') ?><span class="sr-only">Cari kamar</span><input type="search" name="q" value="<?= e($kw) ?>" placeholder="Nomor atau tipe kamar..." autocomplete="off"></label>
    <button type="submit" class="btn btn-primary btn-md">Cari</button>
</form>
<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>No.</th><th>Tipe</th><th>Harga / Bulan</th><th>Status</th><th class="ta-right">Aksi</th></tr></thead>
            <tbody>
            <?php if (empty($daftar)): ?>
                <tr class="table-empty"><td colspan="5"><?= a_ikon('search') ?>Tidak ada kamar.</td></tr>
            <?php else: foreach ($daftar as $r): ?>
                <tr>
                    <td class="fw-600"><?= e($r['nomor_kamar']) ?></td>
                    <td class="cell-wrap"><?= e($r['kelas_kamar']) ?></td>
                    <td class="nowrap"><?= rupiah($r['harga_sewa']) ?></td>
                    <td><?= a_badge($r['status_kamar']) ?></td>
                    <td><div class="row-actions">
                        <a href="edit.php?id=<?= (int) $r['id_kamar'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                        <form class="form-hapus" method="post" action="hapus.php">
                            <input type="hidden" name="id" value="<?= (int) $r['id_kamar'] ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger-outline btn-sm">Hapus</button>
                        </form>
                    </div></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
