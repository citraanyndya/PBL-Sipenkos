<?php
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/pemesanan.php';
wajib_role('pengelola');
$judul_halaman = "Detail Pemesanan";

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare(
    "SELECT p.*, g.nik, g.nama_lengkap, g.no_hp, g.kontak_darurat, g.nama_kontak_darurat, g.hubungan_kontak_darurat, g.foto_ktp, k.nomor_kamar, k.kelas_kamar, k.harga_sewa,
            " . sql_selesai('p') . " AS tgl_selesai
       FROM pemesanan p JOIN penghuni g ON g.id_penghuni = p.id_penghuni JOIN kamar k ON k.id_kamar = p.id_kamar
      WHERE p.id_pemesanan = :id"
);
$stmt->execute(['id' => $id]);
$p = $stmt->fetch();
if (!$p) {
    set_flash('error', 'Pemesanan tidak ditemukan.');
    redirect('list.php');
}
$pb = $pdo->prepare("SELECT * FROM pembayaran WHERE id_pemesanan = :id ORDER BY id_pembayaran");
$pb->execute(['id' => $id]);
$pembayaran = $pb->fetchAll();
$menunggu = array_values(array_filter($pembayaran, fn($b) => $b['status_bayar'] === 'menunggu'));
$bisaDiputuskan = $p['status_pesanan'] === 'menunggu_verifikasi';
$terbayar = total_terbayar($pdo, $id);
$sisa = max($p['total_tagihan'] - $terbayar, 0);
$susulan = $p['status_pesanan'] === 'diterima' && $menunggu;   // pelunasan menunggu verifikasi
$asal = null;
if ($p['id_pemesanan_asal']) {
    $as = $pdo->prepare("SELECT k.nomor_kamar, k.kelas_kamar FROM pemesanan x JOIN kamar k ON k.id_kamar = x.id_kamar WHERE x.id_pemesanan = :id");
    $as->execute(['id' => $p['id_pemesanan_asal']]);
    $asal = $as->fetch();
}
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <a class="back-link" href="list.php">&larr; Data Reservasi</a>
        <h1 class="page-title">Detail Pemesanan #<?= (int) $p['id_pemesanan'] ?></h1>
        <p class="page-subtitle"><?= e($p['nama_lengkap']) ?> · <?= e($p['kelas_kamar']) ?> (Kamar <?= e($p['nomor_kamar']) ?>)</p>
    </div>
    <div class="page-actions"><?= a_badge($p['status_pesanan']) ?></div>
</div>
<div class="card detail-card">
    <div class="detail-list">
        <div class="detail-row"><span class="detail-label">Penghuni</span><span class="detail-value"><?= e($p['nama_lengkap']) ?> (NIK <?= e($p['nik'] ?: '-') ?>)</span></div>
        <div class="detail-row"><span class="detail-label">Kontak</span><span class="detail-value"><?= e($p['no_hp']) ?></span></div>
        <div class="detail-row"><span class="detail-label">Kontak darurat</span><span class="detail-value"><?= $p['kontak_darurat'] ? e(($p['nama_kontak_darurat'] ?: '(nama belum diisi)') . ' — ' . ($p['hubungan_kontak_darurat'] ?: 'hubungan belum diisi') . ' — ' . $p['kontak_darurat']) : '-' ?></span></div>
        <div class="detail-row"><span class="detail-label">KTP</span><span class="detail-value"><?= $p['foto_ktp'] ? '<a class="link" href="' . e(url_berkas('ktp', $p['foto_ktp'])) . '" target="_blank" rel="noopener">Lihat KTP</a>' : '-' ?></span></div>
        <div class="detail-row"><span class="detail-label">Kamar</span><span class="detail-value"><?= e($p['kelas_kamar']) ?> (Kamar <?= e($p['nomor_kamar']) ?>)<?php if ($asal): ?><span class="col-sub">Perpanjangan dari Kamar <?= e($asal['nomor_kamar']) ?> (<?= e($asal['kelas_kamar']) ?>)<?= $asal['nomor_kamar'] !== $p['nomor_kamar'] ? ' — PINDAH KAMAR' : '' ?></span><?php endif; ?></span></div>
        <div class="detail-row"><span class="detail-label">Masa sewa</span><span class="detail-value"><?= tgl_indo($p['tgl_mulai']) ?> – <?= tgl_indo($p['tgl_selesai']) ?> (<?= (int) $p['durasi_bulan'] ?> bulan)</span></div>
        <div class="detail-row"><span class="detail-label">Harga sewa saat pesan</span><span class="detail-value"><?= rupiah($p['harga_sewa_saat_pesan']) ?> / bulan</span></div>
        <div class="detail-row"><span class="detail-label">Total tagihan</span><span class="detail-value fw-600"><?= rupiah($p['total_tagihan']) ?></span></div>
        <div class="detail-row"><span class="detail-label">Sudah diverifikasi</span><span class="detail-value"><?= rupiah($terbayar) ?> · sisa <strong><?= rupiah($sisa) ?></strong> <?= $sisa <= 0 ? a_badge('lunas') : '' ?></span></div>
        <div class="detail-row"><span class="detail-label">Batas hold</span><span class="detail-value"><?= $p['batas_hold'] ? tgl_indo($p['batas_hold'], true) : '- (perpanjangan)' ?></span></div>
    </div>
</div>

<div class="card">
    <div class="card-head card-head-bar"><h2 class="card-title">Bukti Pembayaran</h2></div>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Waktu</th><th>Jenis</th><th>Metode</th><th>Jumlah</th><th>Status</th><th>Alasan Penolakan</th><th>Bukti</th></tr></thead>
            <tbody>
            <?php if (empty($pembayaran)): ?>
                <tr class="table-empty"><td colspan="7">Penghuni belum mengunggah bukti pembayaran.</td></tr>
            <?php else: foreach ($pembayaran as $b): ?>
                <tr>
                    <td class="nowrap"><?= tgl_indo($b['tgl_bayar'], true) ?></td>
                    <td class="nowrap"><?= e(JENIS_BAYAR[$b['jenis_bayar']] ?? $b['jenis_bayar']) ?></td>
                    <td class="nowrap"><?= $b['metode_bayar'] === 'tunai' ? 'Tunai' : 'Transfer' ?></td>
                    <td class="nowrap"><?= rupiah($b['jml_bayar']) ?></td>
                    <td><?= a_badge($b['status_bayar']) ?></td>
                    <td class="cell-wrap"><?= e($b['alasan_penolakan']) ?></td>
                    <td class="nowrap"><a class="link" href="<?= e(url_berkas('bukti', $b['bukti_transfer'])) ?>" target="_blank" rel="noopener"><?= $b['metode_bayar'] === 'tunai' ? 'Lihat Kwitansi' : 'Lihat Bukti Transfer' ?></a></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($susulan): ?>
<h2 class="section-title">Verifikasi Pelunasan</h2>
<div class="decision-grid">
    <div class="card card-pad card-section">
        <h3 class="card-title">Terima Pembayaran</h3>
        <p class="table-note">Pembayaran susulan (pelunasan) dari penghuni menunggu verifikasi.</p>
        <form method="post" action="proses_terima_bayar.php" class="form-konfirmasi" data-konfirmasi="Terima pembayaran susulan ini?" data-ok="Ya, Terima">
            <?= csrf_field() ?>
            <input type="hidden" name="id_pembayaran" value="<?= (int) $menunggu[0]['id_pembayaran'] ?>">
            <input type="hidden" name="id" value="<?= $id ?>">
            <button type="submit" class="btn btn-primary btn-md">Terima Pembayaran</button>
        </form>
    </div>
    <div class="card card-pad card-section">
        <h3 class="card-title">Tolak Bukti</h3>
        <form method="post" action="../pembayaran/proses_tolak.php" class="form-grid">
            <?= csrf_field() ?>
            <input type="hidden" name="id_pembayaran" value="<?= (int) $menunggu[0]['id_pembayaran'] ?>">
            <input type="hidden" name="id_pemesanan" value="<?= $id ?>">
            <div class="field">
                <label class="field-label" for="alasan_susulan">Alasan (penghuni dapat mengirim ulang) <span class="field-required">*</span></label>
                <input class="input" type="text" id="alasan_susulan" name="alasan_penolakan" placeholder="Alasan" required>
            </div>
            <div><button type="submit" class="btn btn-danger-outline btn-md">Tolak Bukti</button></div>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if ($bisaDiputuskan): ?>
<h2 class="section-title">Keputusan Pemilik</h2>
<?php if ($menunggu): ?>
<div class="decision-grid">
    <div class="card card-pad card-section">
        <h3 class="card-title">Terima Pemesanan<?= $menunggu[0]['jenis_bayar'] === 'dp' ? ' (DP)' : '' ?></h3>
        <p class="table-note">Bukti valid? Terima pemesanan agar kamar berstatus terisi.</p>
        <form method="post" action="proses_terima.php" class="form-konfirmasi" data-konfirmasi="Terima pemesanan dan bukti pembayaran ini?" data-ok="Ya, Terima">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $id ?>">
            <button type="submit" class="btn btn-primary btn-md">Terima Pemesanan<?= $menunggu[0]['jenis_bayar'] === 'dp' ? ' (DP)' : '' ?></button>
        </form>
    </div>
    <div class="card card-pad card-section">
        <h3 class="card-title">Tolak Bukti Pembayaran</h3>
        <form method="post" action="../pembayaran/proses_tolak.php" class="form-grid">
            <?= csrf_field() ?>
            <input type="hidden" name="id_pembayaran" value="<?= (int) $menunggu[0]['id_pembayaran'] ?>">
            <input type="hidden" name="id_pemesanan" value="<?= $id ?>">
            <div class="field">
                <label class="field-label" for="alasan_bukti">Alasan (penghuni dapat unggah ulang) <span class="field-required">*</span></label>
                <input class="input" type="text" id="alasan_bukti" name="alasan_penolakan" placeholder="mis. bukti tidak terbaca" required>
            </div>
            <div><button type="submit" class="btn btn-danger-outline btn-md">Tolak Bukti</button></div>
        </form>
    </div>
</div>
<?php else: ?>
<div class="banner banner-info"><div class="banner-body"><?= a_ikon('info') ?><span>Menunggu penghuni mengunggah bukti pembayaran sebelum dapat diterima.</span></div></div>
<?php endif; ?>
<div class="card card-pad card-section">
    <h3 class="card-title">Tolak Seluruh Pemesanan</h3>
    <p class="table-note">Kamar dilepas kembali dan pemesanan dibatalkan.</p>
    <form method="post" action="proses_tolak.php" class="form-grid form-card">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="field">
            <label class="field-label" for="alasan_pemesanan">Alasan (opsional)</label>
            <input class="input" type="text" id="alasan_pemesanan" name="alasan_penolakan" placeholder="Alasan penolakan">
        </div>
        <div><button type="submit" class="btn btn-danger btn-md">Tolak Pemesanan</button></div>
    </form>
</div>
<?php endif; ?>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
