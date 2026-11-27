<?php
$base = '../';
require_once __DIR__ . '/../includes/auth.php';
wajib_role('penghuni');
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fe.php';
fe_wajib_aktif($pdo);

$status = input_str($_GET, 'status', 20);
if (!in_array($status, STATUS_LAPORAN, true)) {
    $status = '';
}

$sql = "SELECT id_laporan, tgl_laporan, deskripsi_laporan, foto_kerusakan, status_laporan
          FROM laporan_kerusakan WHERE id_penghuni = :u";
$par = ['u' => (int) $_SESSION['user_id']];
if ($status !== '') {
    $sql .= " AND status_laporan = :s";
    $par['s'] = $status;
}
$sql .= " ORDER BY id_laporan DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($par);
$daftar = $stmt->fetchAll();

fe_awal_penghuni($pdo, 'Laporan Kerusakan', ['laporan-kerusakan'], 'laporan');
$label = ['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'];
?>
  <div class="judul-baris">
    <div class="laporan-heading">
      <h1>Laporan Kerusakan</h1>
      <p>Laporkan dan pantau perbaikan fasilitas kamar kamu</p>
    </div>
    <a href="buat-laporan-kerusakan.php" class="btn-utama">
      <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
      Buat Laporan
    </a>
  </div>

  <div class="filter-status" role="group" aria-label="Filter status laporan">
    <a class="filter-pill<?= $status === '' ? ' aktif' : '' ?>" href="laporan-kerusakan.php">Semua</a>
    <?php foreach ($label as $nilai => $teks): ?>
      <a class="filter-pill<?= $status === $nilai ? ' aktif' : '' ?>" href="laporan-kerusakan.php?status=<?= e($nilai) ?>"><?= e($teks) ?></a>
    <?php endforeach; ?>
  </div>

  <?php if ($daftar): ?>
  <ul class="daftar-laporan">
    <?php foreach ($daftar as $r): ?>
      <?php [$kategori, $judul, ] = fe_pecah_laporan($r['deskripsi_laporan']); ?>
      <li class="card-box item-laporan">
        <div class="thumb">
          <?php if ($r['foto_kerusakan']): ?>
            <img src="<?= e(url_berkas('kerusakan', $r['foto_kerusakan'])) ?>" alt="">
          <?php else: ?>
            <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
          <?php endif; ?>
        </div>
        <div>
          <h2 class="item-judul"><?= e($judul) ?></h2>
          <p class="item-meta"><?= e($kategori) ?> &nbsp;&middot;&nbsp; <?= e(tgl_indo($r['tgl_laporan'])) ?></p>
        </div>
        <div class="item-kanan">
          <span class="badge-status badge-status--<?= e($r['status_laporan']) ?>"><?= e($label[$r['status_laporan']] ?? $r['status_laporan']) ?></span>
          <a class="link-detail" href="detail-laporan-kerusakan.php?id=<?= (int) $r['id_laporan'] ?>" aria-label="Lihat detail laporan <?= e($judul) ?>">
            Lihat Detail
            <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
  <?php else: ?>
  <div class="card-box kosong">
    <div class="kosong-ikon" aria-hidden="true">
      <svg class="ikon" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
    </div>
    <?php if ($status === ''): ?>
      <h2>Belum ada laporan</h2>
      <p>Ada fasilitas kamar yang rusak? Buat laporan agar pemilik kos bisa segera menindaklanjuti.</p>
    <?php else: ?>
      <h2>Tidak ada laporan berstatus <?= e($label[$status]) ?></h2>
      <p>Coba pilih filter lain untuk melihat laporan kamu yang lain.</p>
    <?php endif; ?>
  </div>
  <?php endif; ?>
<?php fe_akhir_penghuni(); ?>
