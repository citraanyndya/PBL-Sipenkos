<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/validasi.php';
$judul_halaman = "Daftar Fasilitas";

$params = [];
$where = '';
$stmt = $pdo->prepare("SELECT * FROM fasilitas ORDER BY kategori_fasilitas, nama_fasilitas");
$stmt->execute($params);
$daftar = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Fasilitas</h1>
        <p class="page-subtitle">Fasilitas yang dapat dipasang pada kamar atau area bersama</p>
    </div>
    <div class="page-actions"><a class="btn btn-primary btn-md" href="tambah.php">+ Tambah Fasilitas</a></div>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Nama</th><th>Kategori</th><th class="ta-right">Aksi</th></tr></thead>
            <tbody>
            <?php if (empty($daftar)): ?>
                <tr class="table-empty"><td colspan="3">Belum ada data.</td></tr>
            <?php else: foreach ($daftar as $r): ?>
            <tr>
                <td class="fw-600"><?= e($r['nama_fasilitas']) ?></td>
                <td><span class="badge badge-neutral"><span class="badge-dot"></span><span class="badge-label"><?= e($r['kategori_fasilitas']) ?></span></span></td>
                <td><div class="row-actions">
                    <a href="edit.php?id=<?= (int) $r['id_fasilitas'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                    <form class="form-hapus" method="post" action="hapus.php">
                        <input type="hidden" name="id" value="<?= (int) $r['id_fasilitas'] ?>">
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
