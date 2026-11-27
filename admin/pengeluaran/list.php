<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/validasi.php';
$judul_halaman = "Daftar Pengeluaran";

$bulan = input_str($_GET, 'bulan', 7);
$params = [];
$where = '';
if (preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan)) {
    $where = "WHERE tgl_pengeluaran >= :awal AND tgl_pengeluaran < :akhir";
    $params['awal'] = $bulan . '-01';
    $params['akhir'] = date('Y-m-d', strtotime($bulan . '-01 +1 month'));
} else {
    $bulan = '';
}
$stmt = $pdo->prepare("SELECT * FROM pengeluaran_operasional {$where} ORDER BY tgl_pengeluaran DESC, id_pengeluaran DESC");
$stmt->execute($params);
$daftar = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Pengeluaran</h1>
        <p class="page-subtitle">Catat pengeluaran operasional kos untuk menghitung pendapatan bersih</p>
    </div>
    <div class="page-actions"><a class="btn btn-primary btn-md" href="tambah.php">+ Tambah Pengeluaran</a></div>
</div>
<form method="get" action="list.php" class="search-form">
    <div class="field"><label class="field-label" for="bulan">Filter bulan</label><input class="input" type="month" id="bulan" name="bulan" value="<?= e($bulan) ?>"></div>
    <button type="submit" class="btn btn-primary btn-md">Tampilkan</button>
    <?php if ($bulan !== ''): ?><a class="btn btn-secondary btn-md" href="list.php">Reset</a><?php endif; ?>
</form>
<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Tanggal</th><th>Kategori</th><th>Keterangan</th><th>Nominal</th><th class="ta-right">Aksi</th></tr></thead>
            <tbody>
            <?php if (empty($daftar)): ?>
                <tr class="table-empty"><td colspan="5">Belum ada data.</td></tr>
            <?php else: foreach ($daftar as $r): ?>
            <tr>
                <td class="nowrap"><?= tgl_indo($r['tgl_pengeluaran']) ?></td>
                <td><?= e($r['kategori']) ?></td>
                <td class="cell-wrap"><?= e(mb_strimwidth($r['deskripsi_pengeluaran'], 0, 80, '...')) ?></td>
                <td class="nowrap"><?= rupiah($r['jml_biaya']) ?></td>
                <td><div class="row-actions">
                    <a href="edit.php?id=<?= (int) $r['id_pengeluaran'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                    <form class="form-hapus" method="post" action="hapus.php">
                        <input type="hidden" name="id" value="<?= (int) $r['id_pengeluaran'] ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger-outline btn-sm">Hapus</button>
                    </form>
                </div></td>
            </tr>
            <?php endforeach; endif; ?>
            <?php if (!empty($daftar)): ?>
                <tr class="row-total"><th colspan="3">Total</th><th><?= rupiah(array_sum(array_column($daftar, 'jml_biaya'))) ?></th><th></th></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
