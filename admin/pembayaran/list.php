<?php
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
$judul_halaman = "Verifikasi Pembayaran";

$status = input_str($_GET, 'status', 10);
if (!in_array($status, STATUS_BAYAR, true)) {
    $status = '';
}
$sql = "SELECT b.*, p.id_pemesanan, g.nama_lengkap, k.nomor_kamar
          FROM pembayaran b JOIN pemesanan p ON p.id_pemesanan = b.id_pemesanan
          JOIN penghuni g ON g.id_penghuni = p.id_penghuni JOIN kamar k ON k.id_kamar = p.id_kamar";
$params = [];
if ($status !== '') {
    $sql .= " WHERE b.status_bayar = :s";
    $params['s'] = $status;
}
$stmt = $pdo->prepare($sql . " ORDER BY b.id_pembayaran DESC");
$stmt->execute($params);
$daftar = $stmt->fetchAll();
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $status === 'menunggu' ? 'Verifikasi Pembayaran' : 'Tagihan &amp; Riwayat' ?></h1>
        <p class="page-subtitle">Periksa bukti transfer atau foto kwitansi (tunai) dari penghuni, termasuk DP dan pelunasan</p>
    </div>
</div>
<div class="toolbar"><?= a_chips(['' => 'Semua', 'menunggu' => 'Menunggu', 'diterima' => 'Terverifikasi', 'ditolak' => 'Ditolak'], (string) $status) ?></div>
<div class="card">
    <div class="table-wrap">
        <table class="table table-verifikasi">
            <thead><tr><th>Tanggal Kirim</th><th>Penghuni</th><th>Jenis</th><th>Metode</th><th>Jumlah</th><th>Bukti</th><th>Status</th><th class="ta-right">Aksi</th></tr></thead>
            <tbody>
            <?php if (empty($daftar)): ?>
                <tr class="table-empty"><td colspan="8"><?= a_ikon('search') ?>Tidak ada data pembayaran.</td></tr>
            <?php else: foreach ($daftar as $b): ?>
                <tr>
                    <td class="nowrap"><?= tgl_indo($b['tgl_bayar'], true) ?></td>
                    <td><?= a_orang($b['nama_lengkap'], 'Kamar ' . $b['nomor_kamar']) ?></td>
                    <td class="nowrap"><?= e(JENIS_BAYAR[$b['jenis_bayar']] ?? $b['jenis_bayar']) ?></td>
                    <td class="nowrap"><?= $b['metode_bayar'] === 'tunai' ? 'Tunai' : 'Transfer' ?></td>
                    <td class="nowrap"><?= rupiah($b['jml_bayar']) ?></td>
                    <td class="nowrap"><a class="link" href="<?= e(url_berkas('bukti', $b['bukti_transfer'])) ?>" target="_blank" rel="noopener"><?= $b['metode_bayar'] === 'tunai' ? 'Lihat Kwitansi' : 'Lihat Bukti Transfer' ?></a></td>
                    <td><?= a_badge($b['status_bayar'], $b['status_bayar'] === 'diterima' ? 'Terverifikasi' : null) ?></td>
                    <td><div class="row-actions"><a class="btn <?= $b['status_bayar'] === 'menunggu' ? 'btn-primary' : 'btn-secondary' ?> btn-sm" href="../pemesanan/detail.php?id=<?= (int) $b['id_pemesanan'] ?>"><?= $b['status_bayar'] === 'menunggu' ? 'Verifikasi' : 'Detail' ?></a></div></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
