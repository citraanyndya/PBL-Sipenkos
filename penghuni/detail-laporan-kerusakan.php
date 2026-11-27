<?php
$base = '../';
require_once __DIR__ . '/../includes/auth.php';
wajib_role('penghuni');
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fe.php';
fe_wajib_aktif($pdo);

// Hanya laporan milik penghuni yang sedang masuk yang boleh dibuka.
$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare(
    "SELECT l.id_laporan, l.tgl_laporan, l.deskripsi_laporan, l.foto_kerusakan, l.status_laporan,
            k.nomor_kamar, k.kelas_kamar, p.nama_pengelola
       FROM laporan_kerusakan l
       JOIN kamar k ON k.id_kamar = l.id_kamar
       LEFT JOIN pengelola p ON p.id_pengelola = l.id_pengelola
      WHERE l.id_laporan = :i AND l.id_penghuni = :u"
);
$stmt->execute(['i' => $id, 'u' => (int) $_SESSION['user_id']]);
$lap = $stmt->fetch();

fe_awal_penghuni($pdo, 'Detail Laporan Kerusakan', ['laporan-kerusakan'], 'laporan');
?>
  <a href="laporan-kerusakan.php" class="link-kembali">
    <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    Kembali ke Laporan Kerusakan
  </a>

<?php if (!$lap): ?>
  <div class="card-box kosong">
    <h2>Laporan tidak ditemukan</h2>
    <p>Laporan yang kamu cari tidak ada atau bukan milikmu.</p>
    <a href="laporan-kerusakan.php" class="btn-outline">Lihat Semua Laporan</a>
  </div>
<?php else:
  [$kategori, $judul, $isi] = fe_pecah_laporan($lap['deskripsi_laporan']);
  $label = ['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'];
  $urut = ['baru' => 0, 'diproses' => 1, 'selesai' => 2][$lap['status_laporan']] ?? 0;
  $tahap = [
      ['Laporan dikirim', 'Laporan kamu sudah masuk ke pemilik kos.'],
      ['Sedang diproses', 'Pemilik kos menindaklanjuti perbaikan.'],
      ['Selesai', 'Perbaikan dinyatakan selesai oleh pemilik kos.'],
  ];
?>
  <div class="judul-detail">
    <div class="judul-detail-kiri">
      <h1><?= e($judul) ?></h1>
      <span class="badge-status badge-status--<?= e($lap['status_laporan']) ?>"><?= e($label[$lap['status_laporan']]) ?></span>
    </div>
    <p class="id-tiket">ID Tiket: <span>#<?= (int) $lap['id_laporan'] ?></span></p>
  </div>

  <div class="detail-grid">
    <article class="card-box">
      <dl class="info-laporan">
        <div><dt>Kategori</dt><dd><?= e($kategori) ?></dd></div>
        <div><dt>Lokasi</dt><dd>Kamar <?= e($lap['nomor_kamar']) ?> &middot; <?= e($lap['kelas_kamar']) ?></dd></div>
        <div><dt>Tanggal Lapor</dt><dd><?= e(tgl_indo($lap['tgl_laporan'], true)) ?></dd></div>
      </dl>

      <h2 class="sub-judul">Deskripsi</h2>
      <p class="deskripsi-laporan"><?= nl2br(e($isi)) ?></p>

      <?php if ($lap['foto_kerusakan']): ?>
        <h2 class="sub-judul">Foto Kerusakan</h2>
        <img class="foto-laporan" src="<?= e(url_berkas('kerusakan', $lap['foto_kerusakan'])) ?>" alt="Foto kerusakan yang kamu laporkan">
      <?php endif; ?>

      <p class="catatan-pelapor">
        <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        <span>Status laporan hanya bisa diubah oleh pemilik kos.</span>
      </p>
    </article>

    <article class="card-box">
      <h2 class="judul-card">Riwayat Penanganan</h2>
      <ol class="timeline">
        <?php foreach ($tahap as $i => [$t, $ket]): ?>
          <li class="timeline-item <?= $i < $urut ? 'selesai' : ($i === $urut ? 'berjalan' : 'menunggu') ?>">
            <span class="timeline-dot" aria-hidden="true">
              <svg class="ikon" viewBox="0 0 24 24"><?= $i <= $urut ? '<path d="M20 6L9 17l-5-5"/>' : '<circle cx="12" cy="12" r="9"/>' ?></svg>
            </span>
            <div>
              <p class="timeline-judul"><?= e($t) ?></p>
              <p class="timeline-ket"><?= e($ket) ?></p>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>

      <?php if ($lap['nama_pengelola']): ?>
        <div class="catatan-pengelola">
          <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
          <div>
            <p class="catatan-judul">Ditangani oleh</p>
            <p><?= e($lap['nama_pengelola']) ?></p>
          </div>
        </div>
      <?php endif; ?>
    </article>
  </div>
<?php endif; ?>
<?php fe_akhir_penghuni(); ?>
