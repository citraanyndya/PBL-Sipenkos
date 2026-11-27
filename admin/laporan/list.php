<?php
// Fitur 09 - Laporan Kerusakan: daftar laporan dari semua penghuni (sisi pengelola).
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/fe.php';   // fe_pecah_laporan(): pemecah "[Kategori] Judul\nIsi"
$judul_halaman = "Laporan Kerusakan";

$status = input_str($_GET, 'status', 20);
if (!in_array($status, STATUS_LAPORAN, true)) {
    $status = '';
}
$kw = input_str($_GET, 'q', 60);

$hitung = $pdo->query("SELECT status_laporan, COUNT(*) AS n FROM laporan_kerusakan GROUP BY status_laporan")->fetchAll(PDO::FETCH_KEY_PAIR);
$semua = array_sum($hitung);

$where = [];
$params = [];
if ($status !== '') {
    $where[] = "l.status_laporan = :s";
    $params['s'] = $status;
}
if ($kw !== '') {
    $where[] = "(g.nama_lengkap ILIKE :kw OR l.deskripsi_laporan ILIKE :kw OR k.nomor_kamar ILIKE :kw)";
    $params['kw'] = '%' . $kw . '%';
}
$stmt = $pdo->prepare(
    "SELECT l.id_laporan, l.tgl_laporan, l.deskripsi_laporan, l.foto_kerusakan, l.status_laporan,
            g.nama_lengkap, g.no_hp, k.nomor_kamar, k.kelas_kamar
       FROM laporan_kerusakan l
       JOIN penghuni g ON g.id_penghuni = l.id_penghuni
       JOIN kamar k ON k.id_kamar = l.id_kamar
      " . ($where ? 'WHERE ' . implode(' AND ', $where) : '') . " ORDER BY l.id_laporan DESC LIMIT 200"
);
$stmt->execute($params);
$daftar = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Kerusakan</h1>
        <p class="page-subtitle">Laporan kerusakan fasilitas yang dikirim penghuni</p>
    </div>
</div>
<div class="list-section">
    <div class="toolbar">
        <?= a_chips([
            '' => "Semua ($semua)",
            'baru' => 'Belum Dikerjakan (' . ($hitung['baru'] ?? 0) . ')',
            'diproses' => 'Diproses (' . ($hitung['diproses'] ?? 0) . ')',
            'selesai' => 'Selesai (' . ($hitung['selesai'] ?? 0) . ')',
        ], $status) ?>
        <form method="get" action="list.php" class="toolbar-right">
            <?php if ($status !== ''): ?><input type="hidden" name="status" value="<?= e($status) ?>"><?php endif; ?>
            <label class="search"><?= a_ikon('search') ?><span class="sr-only">Cari penghuni / kamar / isi laporan</span><input type="search" name="q" value="<?= e($kw) ?>" placeholder="Cari nama penghuni atau kamar..." autocomplete="off"></label>
            <button type="submit" class="btn btn-primary btn-md">Cari</button>
        </form>
    </div>
    <div class="card" style="margin-top:16px">
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th class="th-wrap">Tanggal<br>Lapor</th><th>Penghuni</th><th>Kamar</th><th>Kerusakan</th><th>Status</th><th class="ta-right">Aksi</th></tr></thead>
                <tbody>
                <?php if (empty($daftar)): ?>
                    <tr class="table-empty"><td colspan="6"><?= a_ikon('search') ?>Belum ada laporan kerusakan.</td></tr>
                <?php else: foreach ($daftar as $r): ?>
                    <?php [$kategori, $judul, ] = fe_pecah_laporan($r['deskripsi_laporan']); ?>
                    <tr>
                        <td class="nowrap"><?= tgl_indo($r['tgl_laporan']) ?></td>
                        <td><?= a_orang($r['nama_lengkap'], $r['no_hp']) ?></td>
                        <td class="cell-wrap">Kamar <?= e($r['nomor_kamar']) ?><span class="col-sub"><?= e($r['kelas_kamar']) ?></span></td>
                        <td class="cell-wrap"><?= e($judul) ?><span class="col-sub"><?= e($kategori) ?><?= $r['foto_kerusakan'] ? ' &middot; ada foto' : '' ?></span></td>
                        <td><?= a_badge($r['status_laporan']) ?></td>
                        <td><div class="row-actions">
                            <?php if ($r['status_laporan'] !== 'selesai'): ?>
                            <form method="post" action="proses_status.php" class="form-konfirmasi" data-konfirmasi="<?= $r['status_laporan'] === 'baru' ? 'Mulai proses' : 'Tandai selesai' ?> laporan dari <?= e($r['nama_lengkap']) ?>?" data-ok="Ya, Lanjut">
                                <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $r['id_laporan'] ?>">
                                <input type="hidden" name="status" value="<?= $r['status_laporan'] === 'baru' ? 'diproses' : 'selesai' ?>">
                                <input type="hidden" name="kembali" value="list">
                                <button type="submit" class="btn btn-primary btn-sm"><?= $r['status_laporan'] === 'baru' ? 'Proses' : 'Selesaikan' ?></button></form>
                            <?php endif; ?>
                            <a class="btn btn-secondary btn-sm" href="detail.php?id=<?= (int) $r['id_laporan'] ?>">Detail</a>
                        </div></td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <p class="table-note" style="margin-top:12px">Status berjalan satu arah: Belum Dikerjakan &rarr; Diproses &rarr; Selesai. Buka Detail untuk melihat foto dan keterangan lengkap dari penghuni.</p>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
