<?php
$base = '../';
require_once __DIR__ . '/../includes/auth.php';
wajib_role('penghuni');
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fe.php';
$sewa = fe_wajib_aktif($pdo);
$u = (int) $_SESSION['user_id'];

$lap = $pdo->prepare("SELECT status_laporan, COUNT(*) OVER () AS n FROM laporan_kerusakan WHERE id_penghuni = :u ORDER BY tgl_laporan DESC LIMIT 1");
$lap->execute(['u' => $u]);
$laporan = $lap->fetch();
$lapLabel = ['baru' => 'Belum dikerjakan', 'diproses' => 'Sedang diproses', 'selesai' => 'Selesai'];

$pb = $pdo->prepare("SELECT b.* FROM pembayaran b JOIN pemesanan p ON p.id_pemesanan = b.id_pemesanan WHERE p.id_penghuni = :u ORDER BY b.tgl_bayar DESC LIMIT 1");
$pb->execute(['u' => $u]);
$bayar = $pb->fetch();
$bayarLabel = ['diterima' => ['Lunas', ''], 'menunggu' => ['Menunggu verifikasi', ' menunggu'], 'ditolak' => ['Ditolak', ' ditolak']];

$berjalan = fe_pemesanan_berjalan($pdo);
// DP sudah diterima tetapi masih ada sisa tagihan?
$sb = $pdo->prepare("SELECT p.id_pemesanan, p.total_tagihan - COALESCE((SELECT SUM(b.jml_bayar) FROM pembayaran b WHERE b.id_pemesanan = p.id_pemesanan AND b.status_bayar = 'diterima'), 0) AS sisa,
        EXISTS (SELECT 1 FROM pembayaran b WHERE b.id_pemesanan = p.id_pemesanan AND b.status_bayar = 'menunggu') AS menunggu
    FROM pemesanan p WHERE p.id_penghuni = :u AND p.status_pesanan = 'diterima' ORDER BY p.id_pemesanan DESC");
$sb->execute(['u' => $u]);
$sisaTagihan = null;
foreach ($sb->fetchAll() as $x) { if ($x['sisa'] > 0 && !$x['menunggu']) { $sisaTagihan = $x; break; } }
// Perpanjangan disetujui yang akan pindah kamar?
$pd = $pdo->prepare("SELECT k.nomor_kamar, k.kelas_kamar, p.tgl_mulai FROM pemesanan p JOIN kamar k ON k.id_kamar = p.id_kamar WHERE p.id_penghuni = :u AND p.status_pesanan = 'diterima' AND p.tgl_mulai > CURRENT_DATE AND p.id_kamar <> :k ORDER BY p.tgl_mulai LIMIT 1");
$pd->execute(['u' => $u, 'k' => $sewa['id_kamar']]);
$pindah = $pd->fetch();
$sisaHari = (int) floor((strtotime($sewa['selesai']) - strtotime(date('Y-m-d'))) / 86400);
if ($berjalan) {
    $alert = $berjalan['menunggu'] > 0
        ? ['Pembayaran untuk Kamar ' . $berjalan['nomor_kamar'] . ' sedang diverifikasi pemilik kos.', 'status-perpanjangan-sewa.php?id=' . $berjalan['id_pemesanan'], 'Lihat Status']
        : ['Pengajuan perpanjangan menunggu pembayaran. Unggah bukti sebelum ' . tgl_indo($berjalan['batas_hold'], true) . '.', 'status-perpanjangan-sewa.php?id=' . $berjalan['id_pemesanan'], 'Bayar Sekarang'];
} elseif ($sisaTagihan) {
    $alert = ['Sisa tagihan ' . rupiah($sisaTagihan['sisa']) . ' belum dilunasi.', '../calon-penghuni/pembayaran.php?id=' . $sisaTagihan['id_pemesanan'], 'Bayar Sisa'];
} elseif ($pindah) {
    $alert = ['Perpanjangan disetujui: mulai ' . tgl_indo($pindah['tgl_mulai']) . ' kamu pindah ke Kamar ' . $pindah['nomor_kamar'] . ' (' . $pindah['kelas_kamar'] . ').', 'tagihan.php', 'Lihat Tagihan'];
} elseif ($sisaHari <= 30) {
    $alert = ['Masa sewa berakhir ' . tgl_indo($sewa['selesai']) . ' (' . max($sisaHari, 0) . ' hari lagi). Ajukan perpanjangan agar kamarmu tetap aman.', 'perpanjangan-sewa.php', 'Perpanjang'];
} else {
    $alert = null;
}
fe_awal_penghuni($pdo, 'Dashboard Penghuni', ['dashboard'], 'dashboard');
?>
  <div class="welcome-heading">
    <h1>Selamat datang, <span id="welcomeName"><?= e($_SESSION['nama']) ?></span></h1>
    <p id="roomSubtitle">Kamar <?= e($sewa['nomor_kamar']) ?> · <?= e($sewa['kelas_kamar']) ?></p>
  </div>
  <?php if ($alert): ?>
  <section class="bill-alert" id="billAlert">
    <div class="bill-alert-copy"><span class="alert-clock">◷</span><span id="billMessage"><?= e($alert[0]) ?></span></div>
    <a href="<?= e($alert[1]) ?>" class="primary-button"><?= e($alert[2]) ?></a>
  </section>
  <?php endif; ?>
  <section class="summary-grid" aria-label="Ringkasan penghuni">
    <article class="summary-card"><p class="card-label">Masa Sewa</p><h2 id="rentalPeriod"><?= e(tgl_indo($sewa['mulai'])) ?> – <?= e(tgl_indo($sewa['selesai'])) ?></h2><a href="perpanjangan-sewa.php">Perpanjang <span>→</span></a></article>
    <article class="summary-card"><p class="card-label">Laporan Kerusakan</p><h2 id="damageStatus"><?= $laporan ? e($lapLabel[$laporan['status_laporan']] ?? '-') : 'Belum ada laporan' ?></h2><a href="laporan-kerusakan.php">Lihat <span>→</span></a></article>
    <article class="summary-card"><p class="card-label">Pembayaran Terakhir</p>
      <div class="last-payment-line"><h2 id="lastPaymentMonth"><?= $bayar ? e(nama_bulan(date('Y-m', strtotime($bayar['tgl_bayar'])))) : '-' ?></h2><?php if ($bayar): $bl = $bayarLabel[$bayar['status_bayar']]; ?><span class="status-pill<?= $bl[1] ?>" id="paymentStatus"><?= e($bl[0]) ?></span><?php endif; ?></div>
      <a href="riwayat-pembayaran.php">Riwayat <span>→</span></a></article>
  </section>
  <section class="room-card">
    <h2 class="section-card-title">Kamar Saya</h2>
    <div class="room-detail">
      <img id="roomImage" src="<?= e(fe_foto_kamar($sewa['foto_kamar'], $sewa['kelas_kamar'])) ?>" alt="Foto kamar penghuni">
      <div class="room-info">
        <h3 id="roomTitle"><?= e($sewa['kelas_kamar']) ?> · No. <?= e($sewa['nomor_kamar']) ?></h3>
        <p class="room-price" id="roomPrice"><?= e(rupiah($sewa['harga_kontrak'] ?? $sewa['harga_sewa'])) ?> / bulan</p>
        <p class="room-period">Periode sewa: <span id="roomPeriod"><?= e(tgl_indo($sewa['mulai'])) ?> – <?= e(tgl_indo($sewa['selesai'])) ?></span></p>
        <a href="detail-kamar.php" class="secondary-button" id="roomDetailButton">Lihat Detail Kamar</a>
      </div>
    </div>
  </section>
<?php fe_akhir_penghuni(); ?>
