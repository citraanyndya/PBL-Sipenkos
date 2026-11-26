<?php
$base = '../';
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fe.php';
require_once __DIR__ . '/../includes/fe_data.php';

$kelas = input_str($_GET, 'kelas', 50);
if ($kelas === '' && ($n = input_int($_GET, 'tipe'))) {   // tautan lama ?tipe=N
    $c = $pdo->prepare("SELECT kelas_kamar FROM kamar WHERE kelas_kamar LIKE :p LIMIT 1");
    $c->execute(['p' => "Tipe $n %"]);
    $kelas = (string) $c->fetchColumn();
}
$st = $pdo->prepare("SELECT kelas_kamar, COALESCE(MIN(harga_sewa) FILTER (WHERE status_kamar = 'tersedia'), MIN(harga_sewa)) AS harga, COUNT(*) AS total, COUNT(*) FILTER (WHERE status_kamar = 'tersedia') AS sisa, MAX(foto_kamar) AS foto
                       FROM kamar WHERE kelas_kamar = :k GROUP BY kelas_kamar");
$st->execute(['k' => $kelas]);
$t = $st->fetch();
if (!$t) {
    set_flash('error', 'Tipe kamar tidak ditemukan.');
    redirect('daftar-kamar.php');
}
$f = $pdo->prepare("SELECT DISTINCT f.nama_fasilitas, f.kategori_fasilitas FROM kamar k JOIN kamar_fasilitas kf ON kf.id_kamar = k.id_kamar
                      JOIN fasilitas f ON f.id_fasilitas = kf.id_fasilitas WHERE k.kelas_kamar = :k ORDER BY f.kategori_fasilitas, f.nama_fasilitas");
$f->execute(['k' => $kelas]);
$fas = ['Kamar' => [], 'Umum' => []];
foreach ($f->fetchAll() as $r) { $fas[$r['kategori_fasilitas']][] = $r['nama_fasilitas']; }
$no = preg_match('/^Tipe (\d)/', $kelas, $m) ? (int) $m[1] : 0;
$info = FE_TIPE[$no] ?? ['deskripsi' => 'Kamar nyaman di Kos Bu Nining.', 'galeri' => []];
$penuh = (int) $t['sisa'] < 1;
$role = $_SESSION['role'] ?? '';
$urlForm = 'form-sewa.php?kelas=' . urlencode($kelas);
if (!$role) { $_SESSION['setelah_login'] = $_SERVER['REQUEST_URI']; }   // setelah login kembali ke halaman ini
fe_awal_calon($pdo, $kelas, ['detail-kamar'], 'kamar');
?>
  <section class="section-halaman">
    <div class="container">
      <?php fe_flash(); ?>
      <a href="daftar-kamar.php" class="back-link">← Kembali ke Daftar Kamar</a>
      <div class="detail-header">
        <h1 class="tipe-kamar"><?= e($kelas) ?></h1>
        <div class="lokasi-tag"><span class="icon">📍</span> Jl. Pisang Agung III No. 29, Malang</div>
      </div>
      <div class="detail-grid" data-kelas="<?= e($kelas) ?>" id="detailKamar" data-api="../kamar/ketersediaan.php">
        <div class="galeri-detail">
          <div class="card-galeri"><img src="<?= e(fe_foto_kamar($t['foto'], $kelas)) ?>" alt="Foto utama <?= e($kelas) ?>" class="foto-utama" /></div>
          <div class="galeri-tambahan" aria-label="Foto tambahan kamar">
            <?php foreach ($info['galeri'] as [$src, $label]): ?>
              <figure class="foto-pendukung"><img src="../assets/images/<?= e($src) ?>" alt="<?= e($label) ?> pada <?= e($kelas) ?>" loading="lazy"><figcaption><?= e($label) ?></figcaption></figure>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="card-box reservasi-box">
          <div class="status-group"><span class="label">Status Ketersediaan</span><span id="statusSisa"><?= fe_badge_sisa((int) $t['sisa']) ?></span></div>
          <div class="harga-container">
            <span class="label">Biaya Sewa</span>
            <div class="harga"><span class="nominal"><?= e(rupiah($t['harga'])) ?></span> / bulan</div>
            <div class="total-durasi-box" aria-live="polite" hidden>
              <span class="total-durasi-caption">Total biaya untuk <strong id="durasiTerpilih"></strong> bulan</span>
              <strong class="total-durasi-nominal" id="totalHargaDurasi">Rp -</strong>
            </div>
          </div>
          <div class="durasi-container">
            <span class="label">Pilihan Durasi Sewa</span>
            <div class="durasi-group" data-harga="<?= (int) $t['harga'] ?>" data-awal="6">
              <?php foreach (DURASI_SEWA as $n => $lbl): ?>
                <button type="button" class="btn btn-outline btn-durasi" data-durasi="<?= $n ?>" aria-pressed="false"><?= e($lbl) ?></button>
              <?php endforeach; ?>
            </div>
          </div>
          <?php if ($penuh): ?>
            <span class="btn btn-outline btn-full btn-reservasi btn-disabled" id="btnReservasi">Kamar Penuh</span>
          <?php elseif ($role === 'penghuni'): ?>
            <a href="<?= e($urlForm) ?>&amp;durasi=6" data-dasar="<?= e($urlForm) ?>" class="btn btn-primary btn-full btn-reservasi" id="btnReservasi">Reservasi Sekarang</a>
          <?php elseif ($role === 'pengelola'): ?>
            <span class="btn btn-outline btn-full btn-reservasi btn-disabled">Akun pengelola tidak dapat memesan</span>
          <?php else: ?>
            <a href="login.php" class="btn btn-primary btn-full btn-reservasi">Reservasi Sekarang</a>
            <p class="catatan-reservasi">Kamu perlu masuk atau <a href="registrasi.php">daftar akun</a> untuk reservasi</p>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-box deskripsi-box">
        <h2 class="judul-box">Deskripsi Kamar</h2>
        <p class="deskripsi-kamar"><?= e($info['deskripsi']) ?></p>
        <div class="fasilitas-wrapper"><h3 class="subjudul-box">Fasilitas Kamar</h3>
          <ul class="fasilitas"><?php foreach ($fas['Kamar'] as $n): ?><li class="item-fasilitas"><span class="icon"><?= FE_IKON_FASILITAS[$n] ?? '✔️' ?></span> <span><?= e($n) ?></span></li><?php endforeach; ?></ul></div>
        <div class="fasilitas-wrapper"><h3 class="subjudul-box">Fasilitas Bersama &amp; Umum</h3>
          <ul class="fasilitas"><?php foreach ($fas['Umum'] as $n): ?><li class="item-fasilitas"><span class="icon"><?= FE_IKON_FASILITAS[$n] ?? '✔️' ?></span> <span><?= e($n) ?></span></li><?php endforeach; ?></ul></div>
      </div>
    </div>
  </section>
<?php fe_akhir_calon(['calon-penghuni/ketersediaan']); ?>
