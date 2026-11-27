<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
$judul_halaman = "Data Reservasi";

$status = input_str($_GET, 'status', 20);
if (!in_array($status, STATUS_PEMESANAN, true)) {
    $status = '';
}
$kw = input_str($_GET, 'q', 60);

$hitung = $pdo->query("SELECT status_pesanan, COUNT(*) AS n FROM pemesanan GROUP BY status_pesanan")->fetchAll(PDO::FETCH_KEY_PAIR);
$semua = array_sum($hitung);

$where = [];
$params = [];
if ($status !== '') {
    $where[] = "p.status_pesanan = :s";
    $params['s'] = $status;
}
if ($kw !== '') {
    $where[] = "(g.nama_lengkap ILIKE :kw OR g.no_hp ILIKE :kw)";
    $params['kw'] = '%' . $kw . '%';
}
$stmt = $pdo->prepare(
    "SELECT p.id_pemesanan, p.tgl_pemesanan, p.durasi_bulan, p.total_tagihan, p.status_pesanan,
            g.nama_lengkap, g.no_hp, k.nomor_kamar, k.kelas_kamar,
            (SELECT COUNT(*) FROM pembayaran b WHERE b.id_pemesanan = p.id_pemesanan AND b.status_bayar = 'menunggu') AS menunggu
       FROM pemesanan p JOIN penghuni g ON g.id_penghuni = p.id_penghuni JOIN kamar k ON k.id_kamar = p.id_kamar
      " . ($where ? 'WHERE ' . implode(' AND ', $where) : '') . " ORDER BY p.id_pemesanan DESC LIMIT 200"
);
$stmt->execute($params);
$daftar = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Data Reservasi</h1>
        <p class="page-subtitle">Daftar pengajuan pemesanan kamar dari calon penghuni</p>
    </div>
</div>
<div class="list-section">
    <div class="toolbar">
        <?= a_chips([
            '' => "Semua ($semua)",
            'menunggu_verifikasi' => 'Menunggu (' . ($hitung['menunggu_verifikasi'] ?? 0) . ')',
            'diterima' => 'Diterima (' . ($hitung['diterima'] ?? 0) . ')',
            'ditolak' => 'Ditolak (' . ($hitung['ditolak'] ?? 0) . ')',
            'kedaluwarsa' => 'Kedaluwarsa (' . ($hitung['kedaluwarsa'] ?? 0) . ')',
        ], $status) ?>
        <form method="get" action="list.php" class="toolbar-right">
            <?php if ($status !== ''): ?><input type="hidden" name="status" value="<?= e($status) ?>"><?php endif; ?>
            <label class="search"><?= a_ikon('search') ?><span class="sr-only">Cari nama / no. HP</span><input type="search" name="q" value="<?= e($kw) ?>" placeholder="Cari nama calon penghuni..." autocomplete="off"></label>
            <button type="submit" class="btn btn-primary btn-md">Cari</button>
        </form>
    </div>
    <div class="card" style="margin-top:16px">
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th class="th-wrap">Tanggal<br>Pengajuan</th><th>Calon Penghuni</th><th>Tipe Kamar</th><th>Durasi</th><th>Status</th><th class="ta-right">Aksi</th></tr></thead>
                <tbody>
                <?php if (empty($daftar)): ?>
                    <tr class="table-empty"><td colspan="6"><?= a_ikon('search') ?>Tidak ada pemesanan.</td></tr>
                <?php else: foreach ($daftar as $r): ?>
                    <tr>
                        <td class="nowrap"><?= tgl_indo($r['tgl_pemesanan']) ?></td>
                        <td><?= a_orang($r['nama_lengkap'], $r['no_hp']) ?></td>
                        <td class="cell-wrap"><?= e($r['kelas_kamar']) ?><span class="col-sub">Kamar <?= e($r['nomor_kamar']) ?></span></td>
                        <td class="nowrap"><?= (int) $r['durasi_bulan'] ?> Bulan</td>
                        <td><?= a_badge($r['status_pesanan']) ?></td>
                        <td><div class="row-actions">
                            <?php if ($r['status_pesanan'] === 'menunggu_verifikasi' && (int) $r['menunggu'] > 0): ?>
                            <form method="post" action="proses_terima.php" class="form-konfirmasi" data-konfirmasi="Terima pemesanan <?= e($r['nama_lengkap']) ?>?" data-ok="Ya, Terima">
                                <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $r['id_pemesanan'] ?>"><input type="hidden" name="kembali" value="list">
                                <button type="submit" class="btn btn-primary btn-sm">Terima</button></form>
                            <form method="post" action="proses_tolak.php" class="form-konfirmasi" data-konfirmasi="Tolak pemesanan <?= e($r['nama_lengkap']) ?>?" data-tone="danger" data-ok="Ya, Tolak">
                                <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $r['id_pemesanan'] ?>"><input type="hidden" name="kembali" value="list">
                                <button type="submit" class="btn btn-danger-outline btn-sm">Tolak</button></form>
                            <?php endif; ?>
                            <a class="btn btn-secondary btn-sm" href="detail.php?id=<?= (int) $r['id_pemesanan'] ?>">Detail</a>
                        </div></td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <p class="table-note" style="margin-top:12px">Aksi cepat Terima/Tolak muncul setelah penghuni mengunggah bukti transfer. Untuk menolak hanya bukti (penghuni bisa unggah ulang) atau memberi alasan, buka Detail.</p>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
