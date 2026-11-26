<?php
$base = '../';
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fe.php';

$filter = input_str($_GET, 'jenis', 10);
$boleh = ['', 'ac', 'non-ac', 'km-dalam', 'km-luar'];
if (!in_array($filter, $boleh, true)) { $filter = ''; }
$q = input_str($_GET, 'q', 60);
$tipe = fe_daftar_tipe($pdo, $filter, $q);
$tab = ['' => 'Semua', 'ac' => 'AC', 'non-ac' => 'Non-AC', 'km-dalam' => 'Kamar Mandi Dalam', 'km-luar' => 'Kamar Mandi Luar'];
fe_awal_calon($pdo, 'Daftar Kamar', ['kamar'], 'kamar');
?>
  <section class="section-halaman">
    <div class="container">
      <?php fe_flash(); ?>
      <div class="section-header">
        <h1 class="judul-section">Daftar Kamar Kos</h1>
        <p class="deskripsi">Pilih tipe kamar yang sesuai kebutuhan kamu</p>
      </div>
      <form method="get" class="filter-kamar">
        <input type="hidden" name="jenis" value="<?= e($filter) ?>">
        <input type="search" name="q" value="<?= e($q) ?>" class="form-input" placeholder="Cari tipe kamar..." aria-label="Cari tipe kamar">
        <button type="submit" class="btn btn-primary">Cari</button>
      </form>
      <div class="chip-filter">
        <?php foreach ($tab as $k => $lbl): ?>
          <a href="?jenis=<?= e($k) ?>&amp;q=<?= urlencode($q) ?>" class="chip<?= $filter === $k ? ' active' : '' ?>"><?= e($lbl) ?></a>
        <?php endforeach; ?>
      </div>
      <div class="kamar-grid" id="kamarGrid" data-api="../kamar/ketersediaan.php">
        <?php foreach ($tipe as $t): ?>
          <div data-kelas="<?= e($t['kelas_kamar']) ?>" class="kartu-tautan"><?= fe_kartu_kamar($t, true) ?></div>
        <?php endforeach; ?>
        <?php if (!$tipe): ?><p class="deskripsi">Tidak ada tipe kamar yang cocok.</p><?php endif; ?>
      </div>
    </div>
  </section>
<?php fe_akhir_calon(['calon-penghuni/ketersediaan']); ?>
