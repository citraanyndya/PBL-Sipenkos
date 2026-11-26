<?php
$base = '../';
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fe.php';

$tipe = fe_daftar_tipe($pdo);
$banner = fe_banner_reservasi($pdo);
fe_awal_calon($pdo, 'Kos Bu Nining Malang', ['beranda', 'kamar'], 'beranda');
?>
  <div class="container"><?php fe_flash(); ?></div>
  <?php if ($banner): ?>
  <section class="container banner-status" id="bannerStatus" aria-live="polite">
    <div class="banner-status-icon"><?= $banner[0] ?></div>
    <div class="banner-status-content"><strong><?= e($banner[1]) ?></strong><p><?= e($banner[2]) ?></p></div>
    <a href="<?= e($banner[3]) ?>" class="btn btn-outline"><?= e($banner[4]) ?></a>
  </section>
  <?php endif; ?>
  <section class="section-image">
    <div class="container-image">
      <div class="image-header">
        <img src="../assets/images/header.jpeg" alt="Interior Kamar SIPENKOS" class="header-image" />
        <div class="content-overlay">
          <h1 class="judul-section">Lagi nyari kos? <span class="text-blue">SIPENKOS</span> aja</h1>
          <p class="deskripsi">Kos Bu Nining, dekat kampus di Malang. Cek kamar kosong dan reservasi langsung dari sini.</p>
        </div>
      </div>
    </div>
  </section>
  <section class="section-fasilitas">
    <div class="container"><div class="fasilitas-wrapper"><ul class="fasilitas fasilitas-grid">
      <li class="item-fasilitas"><span class="icon">📶</span> Wi-Fi</li><li class="item-fasilitas"><span class="icon">🍳</span> Dapur</li>
      <li class="item-fasilitas"><span class="icon">⚡</span> Listrik</li><li class="item-fasilitas"><span class="icon">🛵</span> Parkir</li>
      <li class="item-fasilitas"><span class="icon">🧺</span> Laundry</li><li class="item-fasilitas"><span class="icon">📹</span> CCTV</li>
    </ul></div></div>
  </section>
  <section class="section-halaman">
    <div class="container">
      <div class="section-header">
        <h3 class="judul-section">Pilihan Tipe Kamar</h3>
        <p class="deskripsi">Pilih kamar yang paling pas dengan kebutuhan dan kenyamanan kamu.</p>
      </div>
      <div class="kamar-grid" id="kamarGrid" data-api="../kamar/ketersediaan.php">
        <?php foreach ($tipe as $t): ?>
          <a href="detail-kamar.php?kelas=<?= urlencode($t['kelas_kamar']) ?>" class="kartu-tautan" data-kelas="<?= e($t['kelas_kamar']) ?>"><?= fe_kartu_kamar($t, false) ?></a>
        <?php endforeach; ?>
        <?php if (!$tipe): ?><p class="deskripsi">Belum ada data kamar.</p><?php endif; ?>
      </div>
    </div>
  </section>
<?php fe_akhir_calon(['calon-penghuni/ketersediaan']); ?>
