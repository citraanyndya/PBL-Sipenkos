<?php
$base = '../';
require_once __DIR__ . '/../includes/auth.php';
wajib_role('penghuni');
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fe.php';
require_once __DIR__ . '/../includes/pemesanan.php';
require_once __DIR__ . '/../includes/fe_bayar.php';
$app = require __DIR__ . '/../includes/config.php';
$u = (int) $_SESSION['user_id'];
$id = input_int($_GET, 'id');
$sql = "SELECT p.*, k.nomor_kamar, k.kelas_kamar, " . sql_selesai('p') . " AS selesai FROM pemesanan p JOIN kamar k ON k.id_kamar = p.id_kamar WHERE p.id_penghuni = :u";
$st = $id ? $pdo->prepare($sql . ' AND p.id_pemesanan = :id') : $pdo->prepare($sql . ' ORDER BY p.id_pemesanan DESC LIMIT 1');
$st->execute($id ? ['u' => $u, 'id' => $id] : ['u' => $u]);
$p = $st->fetch();
fe_awal_penghuni($pdo, 'Status Perpanjangan Sewa', ['perpanjangan-sewa'], 'perpanjangan');
?>
  <div class="perpanjangan-heading"><h1>Status Perpanjangan Sewa</h1><p>Pantau pengajuan perpanjangan sewa kamu</p></div>
<?php if (!$p): ?>
  <div class="card-box empty-state"><h2>Belum ada pengajuan perpanjangan</h2><p>Ajukan perpanjangan untuk melanjutkan masa sewa kamu.</p><a href="perpanjangan-sewa.php" class="btn-utama">Ajukan Perpanjangan</a></div>
<?php fe_akhir_penghuni(['bayar']); exit; endif;
$pb = $pdo->prepare('SELECT * FROM pembayaran WHERE id_pemesanan = :id ORDER BY id_pembayaran');
$pb->execute(['id' => $p['id_pemesanan']]);
$bayar = $pb->fetchAll();
$akhir = $bayar ? end($bayar) : null;
$menunggu = $akhir && $akhir['status_bayar'] === 'menunggu';
$stt = $p['status_pesanan'];
if ($stt === 'diterima') { $b = ['diterima', 'Disetujui', 'Perpanjangan disetujui. Masa sewa kamu sudah diperbarui.']; }
elseif ($stt === 'ditolak') { $b = ['ditolak', 'Ditolak', 'Pengajuan ditolak' . (($akhir['alasan_penolakan'] ?? '') ? ': ' . $akhir['alasan_penolakan'] : '.')]; }
elseif ($stt === 'kedaluwarsa') { $b = ['ditolak', 'Kedaluwarsa', 'Pengajuan kedaluwarsa. Silakan ajukan ulang.']; }
elseif ($menunggu) { $b = ['menunggu', 'Menunggu Verifikasi', 'Bukti pembayaran sedang diperiksa pemilik kos.']; }
elseif ($akhir) { $b = ['ditolak', 'Bukti Ditolak', 'Bukti terakhir ditolak: ' . ($akhir['alasan_penolakan'] ?: 'tidak dapat diverifikasi') . '. Unggah ulang bukti transfer.']; }
else { $b = ['menunggu', 'Menunggu Pembayaran', 'Unggah bukti transfer agar pengajuan dapat diverifikasi pemilik kos.']; }
$bisaUnggah = !$menunggu && (($stt === 'menunggu_verifikasi') || ($stt === 'diterima' && $sisa > 0));
$pindahKamar = false;
if ($p['id_pemesanan_asal']) {
    $as = $pdo->prepare('SELECT k.nomor_kamar, k.kelas_kamar FROM pemesanan x JOIN kamar k ON k.id_kamar = x.id_kamar WHERE x.id_pemesanan = :id');
    $as->execute(['id' => $p['id_pemesanan_asal']]);
    $asal = $as->fetch();
    $pindahKamar = $asal && $asal['nomor_kamar'] !== $p['nomor_kamar'] ? $asal : false;
}
$terbayar = array_sum(array_map(fn($x) => $x['status_bayar'] === 'diterima' ? $x['jml_bayar'] : 0, $bayar));
$sisa = max($p['total_tagihan'] - $terbayar, 0);
?>
  <div class="status-banner status-banner--<?= $b[0] ?>" role="status"><span class="status-pill"><?= e($b[1]) ?></span><p><?= e($b[2]) ?></p></div>
  <div class="status-grid">
    <article class="card-box detail-card">
      <h2 class="judul-card">Detail Pengajuan</h2>
      <dl class="panel-detail">
        <div><dt>Kamar</dt><dd><?= e($p['kelas_kamar']) ?> · No. <?= e($p['nomor_kamar']) ?></dd></div>
        <div><dt>Durasi</dt><dd><?= (int) $p['durasi_bulan'] ?> bulan</dd></div>
        <?php if ($pindahKamar): ?><div><dt>Pindah dari</dt><dd>Kamar <?= e($pindahKamar['nomor_kamar']) ?> (<?= e($pindahKamar['kelas_kamar']) ?>)</dd></div><?php endif; ?>
        <div><dt>Masa sewa baru</dt><dd><?= e(tgl_indo($p['tgl_mulai'])) ?> – <?= e(tgl_indo($p['selesai'])) ?></dd></div>
        <div class="panel-pemisah"><dt>Total Tagihan</dt><dd><?= e(rupiah($p['total_tagihan'])) ?></dd></div>
        <div class="baris-rapat"><dt>Sudah terverifikasi</dt><dd><?= e(rupiah($terbayar)) ?></dd></div>
        <div class="baris-rapat"><dt>Sisa Tagihan</dt><dd><?= e(rupiah(max($p['total_tagihan'] - $terbayar, 0))) ?></dd></div>
      </dl>
      <?php if ($bisaUnggah): ?>
      <div class="aksi-detail"><?php fe_form_bayar($p, (float) $terbayar, 'perpanjangan', $app); ?></div>
      <?php endif; ?>
      <div class="detail-footer"><span class="nomor-permohonan">No. Permohonan: PRJ-<?= str_pad((string) $p['id_pemesanan'], 5, '0', STR_PAD_LEFT) ?></span><span>Diajukan secara mandiri</span></div>
    </article>
    <article class="card-box">
      <h2 class="judul-card">Proses Perpanjangan</h2>
      <ol class="timeline">
        <li class="timeline-item selesai"><span class="timeline-dot">✓</span><div><p class="timeline-judul">Pengajuan dikirim</p><p class="timeline-ket"><?= e(tgl_indo($p['tgl_pemesanan'], true)) ?></p></div></li>
        <?php foreach ($bayar as $x): ?>
          <li class="timeline-item <?= $x['status_bayar'] === 'ditolak' ? 'ditolak' : 'selesai' ?>"><span class="timeline-dot"><?= $x['status_bayar'] === 'ditolak' ? '✕' : '✓' ?></span><div><p class="timeline-judul">Bukti pembayaran diunggah</p><p class="timeline-ket"><?= e(tgl_indo($x['tgl_bayar'], true)) ?><?= $x['status_bayar'] === 'ditolak' ? ' · ditolak: ' . e($x['alasan_penolakan']) : '' ?></p></div></li>
        <?php endforeach; ?>
        <?php if (!$bayar): ?><li class="timeline-item aktif"><span class="timeline-dot">◷</span><div><p class="timeline-judul">Menunggu bukti pembayaran</p><p class="timeline-ket">Unggah bukti transfer</p></div></li><?php endif; ?>
        <li class="timeline-item <?= $stt === 'diterima' ? 'selesai' : ($menunggu ? 'aktif' : 'menunggu') ?>"><span class="timeline-dot"><?= $stt === 'diterima' ? '✓' : '◷' ?></span><div><p class="timeline-judul">Verifikasi pemilik kos</p><p class="timeline-ket"><?= $stt === 'diterima' ? 'Selesai' : ($menunggu ? 'Sedang diproses' : 'Menunggu') ?></p></div></li>
        <li class="timeline-item <?= $stt === 'diterima' ? 'selesai' : 'menunggu' ?>"><span class="timeline-dot"><?= $stt === 'diterima' ? '✓' : '●' ?></span><div><p class="timeline-judul"><?= $pindahKamar ? 'Pindah ke Kamar ' . e($p['nomor_kamar']) : 'Masa sewa diperbarui' ?></p><p class="timeline-ket"><?= $stt === 'diterima' ? 'Berlaku sampai ' . e(tgl_indo($p['selesai'])) : 'Setelah pembayaran terverifikasi' ?></p></div></li>
      </ol>
    </article>
  </div>
<?php fe_akhir_penghuni(['bayar']); ?>
