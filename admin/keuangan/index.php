<?php
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
$judul_halaman = "Laporan Keuangan";

$bulan = $_GET['bulan'] ?? date('Y-m');
if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan)) {
    $bulan = date('Y-m');
}
$awal = $bulan . '-01';
$akhir = date('Y-m-d', strtotime($awal . ' +1 month'));
$periode = BULAN_INDO[(int) substr($bulan, 5, 2)] . ' ' . substr($bulan, 0, 4);

// Pendapatan kotor = pembayaran yang DITERIMA pemilik pada bulan tsb (berdasarkan tgl_verifikasi: saat uang diakui,
// bukan saat bukti diunggah). Nominal = jml_bayar yang tercatat, jadi tidak terpengaruh perubahan harga kamar.
$kotor = $pdo->prepare(
    "SELECT COALESCE(SUM(jml_bayar), 0) AS total, COUNT(*) AS jumlah FROM pembayaran
      WHERE status_bayar = 'diterima' AND tgl_verifikasi >= :awal AND tgl_verifikasi < :akhir"
);
$kotor->execute(['awal' => $awal, 'akhir' => $akhir]);
$kotor = $kotor->fetch();

$keluar = $pdo->prepare(
    "SELECT COALESCE(SUM(jml_biaya), 0) AS total, COUNT(*) AS jumlah FROM pengeluaran_operasional
      WHERE tgl_pengeluaran >= :awal AND tgl_pengeluaran < :akhir"
);
$keluar->execute(['awal' => $awal, 'akhir' => $akhir]);
$keluar = $keluar->fetch();

$perKamar = $pdo->prepare(
    "SELECT k.nomor_kamar, k.kelas_kamar, SUM(b.jml_bayar) AS pendapatan
       FROM pembayaran b JOIN pemesanan p ON p.id_pemesanan = b.id_pemesanan JOIN kamar k ON k.id_kamar = p.id_kamar
      WHERE b.status_bayar = 'diterima' AND b.tgl_verifikasi >= :awal AND b.tgl_verifikasi < :akhir
      GROUP BY k.id_kamar, k.nomor_kamar, k.kelas_kamar ORDER BY k.nomor_kamar"
);
$perKamar->execute(['awal' => $awal, 'akhir' => $akhir]);
$perKamar = $perKamar->fetchAll();

$pengeluaran = $pdo->prepare(
    "SELECT * FROM pengeluaran_operasional WHERE tgl_pengeluaran >= :awal AND tgl_pengeluaran < :akhir ORDER BY tgl_pengeluaran"
);
$pengeluaran->execute(['awal' => $awal, 'akhir' => $akhir]);
$pengeluaran = $pengeluaran->fetchAll();

$bersih = $kotor['total'] - $keluar['total'];
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Keuangan</h1>
        <p class="page-subtitle">Pantau pendapatan, pengeluaran operasional, dan pendapatan bersih · <?= e($periode) ?></p>
    </div>
    <div class="page-actions"><a class="btn btn-primary btn-md" href="../pengeluaran/tambah.php">+ Tambah Pengeluaran</a></div>
</div>
<form method="get" action="index.php" class="search-form">
    <div class="field"><label class="field-label" for="bulan">Periode</label><input class="input" type="month" id="bulan" name="bulan" value="<?= e($bulan) ?>"></div>
    <button type="submit" class="btn btn-primary btn-md">Tampilkan</button>
</form>
<section class="stats stats-3" aria-label="Ringkasan keuangan">
    <div class="card stat stat-money"><div class="stat-top"><span class="icon-box icon-box-sm tone-green"><?= a_ikon('trending-up') ?></span><p class="stat-label">Pendapatan Kotor</p></div>
        <p class="stat-value"><?= rupiah($kotor['total']) ?></p><p class="stat-note"><?= (int) $kotor['jumlah'] ?> pembayaran diterima</p></div>
    <div class="card stat stat-money"><div class="stat-top"><span class="icon-box icon-box-sm tone-red"><?= a_ikon('trending-down') ?></span><p class="stat-label">Total Pengeluaran</p></div>
        <p class="stat-value"><?= rupiah($keluar['total']) ?></p><p class="stat-note"><?= (int) $keluar['jumlah'] ?> transaksi</p></div>
    <div class="card stat stat-money"><div class="stat-top"><span class="icon-box icon-box-sm tone-blue"><?= a_ikon('wallet') ?></span><p class="stat-label">Pendapatan Bersih</p></div>
        <p class="stat-value <?= $bersih < 0 ? 'text-negative' : 'stat-value-primary' ?>"><?= rupiah($bersih) ?></p><p class="stat-note">Pendapatan kotor − pengeluaran</p></div>
</section>
<div class="card">
    <div class="card-head card-head-bar"><h2 class="card-title">Pendapatan Kotor per Kamar</h2></div>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Kamar</th><th>Tipe</th><th>Pendapatan</th></tr></thead>
            <tbody>
            <?php if (empty($perKamar)): ?>
                <tr class="table-empty"><td colspan="3">Belum ada pendapatan pada periode ini.</td></tr>
            <?php else: foreach ($perKamar as $r): ?>
                <tr><td class="fw-600"><?= e($r['nomor_kamar']) ?></td><td class="cell-wrap"><?= e($r['kelas_kamar']) ?></td><td class="nowrap"><?= rupiah($r['pendapatan']) ?></td></tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="card">
    <div class="card-head card-head-bar"><h2 class="card-title">Pengeluaran Operasional</h2><a class="link" href="../pengeluaran/list.php">Kelola Pengeluaran</a></div>
    <div class="table-wrap">
        <table class="table table-keuangan">
            <thead><tr><th>Tanggal</th><th>Kategori</th><th>Keterangan</th><th>Nominal</th></tr></thead>
            <tbody>
            <?php if (empty($pengeluaran)): ?>
                <tr class="table-empty"><td colspan="4">Belum ada pengeluaran pada periode ini.</td></tr>
            <?php else: foreach ($pengeluaran as $r): ?>
                <tr><td class="nowrap"><?= tgl_indo($r['tgl_pengeluaran']) ?></td><td><?= e($r['kategori']) ?></td>
                    <td class="cell-wrap"><?= e($r['deskripsi_pengeluaran']) ?></td><td class="nowrap"><?= rupiah($r['jml_biaya']) ?></td></tr>
            <?php endforeach; ?>
                <tr class="row-total"><th colspan="3">Total</th><th><?= rupiah($keluar['total']) ?></th></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
