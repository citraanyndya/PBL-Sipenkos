<?php
$base = '../';
require_once __DIR__ . '/../includes/auth.php';
wajib_role('penghuni');
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fe.php';
$sewa = fe_wajib_aktif($pdo);
$berjalan = fe_pemesanan_berjalan($pdo);
$tipe = fe_daftar_tipe($pdo);
fe_awal_penghuni($pdo, 'Perpanjangan Sewa', ['perpanjangan-sewa'], 'perpanjangan');
?>
  <div class="perpanjangan-heading"><h1>Perpanjangan Sewa</h1><p>Pilih durasi untuk memperpanjang masa sewa kamu</p></div>
  <?php if ($berjalan): ?>
    <div class="info-pengajuan info-pengajuan--menunggu_verifikasi" role="status">
      <p>Kamu masih memiliki pengajuan yang belum selesai (<?= $berjalan['menunggu'] > 0 ? 'sedang diverifikasi' : 'menunggu bukti pembayaran' ?>).</p>
      <a href="status-perpanjangan-sewa.php?id=<?= (int) $berjalan['id_pemesanan'] ?>" class="btn-outline">Lihat Status Pengajuan</a>
    </div>
  <?php else: ?>
  <form class="card-box form-perpanjangan" id="formPerpanjangan" action="../reservasi/proses_perpanjang.php" method="post" data-mulai="<?= e($sewa['selesai']) ?>">
    <?= csrf_field() ?>
    <div class="info-sewa">
      <p class="label-kecil">Informasi Kamar &amp; Sewa Saat Ini</p>
      <h2 id="infoKamar"><?= e($sewa['kelas_kamar']) ?> · No. <?= e($sewa['nomor_kamar']) ?></h2>
      <p class="info-periode" id="infoPeriode">Masa sewa saat ini <?= e(tgl_indo($sewa['mulai'])) ?> – <?= e(tgl_indo($sewa['selesai'])) ?></p>
    </div>
    <fieldset class="grup-pilihan">
      <legend>Pilih Tipe Kamar (tetap atau pindah)</legend>
      <div class="opsi-tipe">
        <input type="hidden" name="harga_tampil[<?= e($sewa['kelas_kamar']) ?>]" value="<?= (int) $sewa['harga_sewa'] ?>">
        <label class="opsi-bayar aktif"><input type="radio" name="kelas" value="<?= e($sewa['kelas_kamar']) ?>" data-harga="<?= (int) $sewa['harga_sewa'] ?>" data-nama="<?= e($sewa['kelas_kamar']) ?> (kamar tetap)" checked>
          <span><strong>Tetap di kamar saya</strong><small><?= e($sewa['kelas_kamar']) ?> · No. <?= e($sewa['nomor_kamar']) ?> — <?= e(rupiah($sewa['harga_sewa'])) ?>/bulan</small></span></label>
        <?php foreach ($tipe as $t): if ($t['kelas_kamar'] === $sewa['kelas_kamar']) { continue; } $ada = (int) $t['sisa'] > 0; ?>
          <input type="hidden" name="harga_tampil[<?= e($t['kelas_kamar']) ?>]" value="<?= (int) $t['harga'] ?>">
          <label class="opsi-bayar<?= $ada ? '' : ' nonaktif' ?>"><input type="radio" name="kelas" value="<?= e($t['kelas_kamar']) ?>" data-harga="<?= (int) $t['harga'] ?>" data-nama="Pindah ke <?= e($t['kelas_kamar']) ?>"<?= $ada ? '' : ' disabled' ?>>
            <span><strong>Pindah ke <?= e($t['kelas_kamar']) ?></strong><small><?= e(rupiah($t['harga'])) ?>/bulan · <?= $ada ? 'tersedia ' . (int) $t['sisa'] . ' kamar (kamar ditahan untukmu setelah mengajukan)' : 'penuh' ?></small></span></label>
        <?php endforeach; ?>
      </div>
    </fieldset>
    <fieldset class="grup-pilihan">
      <legend>Pilih Durasi Perpanjangan</legend>
      <div class="pilihan pilihan-3">
        <?php foreach (DURASI_SEWA as $n => $lbl): ?><label class="opsi"><input type="radio" name="durasi_bulan" value="<?= $n ?>"<?= $n === 6 ? ' checked' : '' ?>><span><?= e($lbl) ?></span></label><?php endforeach; ?>
      </div>
    </fieldset>
    <p class="catatan">Harga di atas adalah tarif yang berlaku saat ini dan akan dikunci untuk masa perpanjangan ini; perubahan tarif berikutnya tidak memengaruhi pengajuanmu.</p>
    <p class="catatan">Pembayaran dipilih setelah pengajuan: bayar penuh atau DP, via transfer atau tunai (foto kwitansi).</p>
    <ul class="ringkasan" aria-live="polite">
      <li><span>Kamar: <b id="ringKamar">-</b></span></li>
      <li><span>Masa sewa baru: <b id="ringMasa">-</b></span></li>
      <li><span>Tagihan <b id="ringTotal">-</b></span></li>
    </ul>
    <div class="aksi-form"><button type="submit" class="btn-utama">Ajukan Perpanjangan</button></div>
  </form>
  <?php endif; ?>
<?php fe_akhir_penghuni(['penghuni/perpanjangan-sewa']); ?>
