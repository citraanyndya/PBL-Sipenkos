<?php
$base = '../';
require_once __DIR__ . '/../includes/auth.php';
wajib_role('penghuni');
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fe.php';
$sewa = fe_wajib_aktif($pdo);
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
fe_awal_penghuni($pdo, 'Buat Laporan Kerusakan', ['laporan-kerusakan'], 'laporan');
?>
  <div class="laporan-heading">
    <h1>Buat Laporan Baru</h1>
    <p>Jelaskan kerusakan agar pemilik kos bisa segera menindaklanjuti</p>
  </div>

  <form class="card-box form-laporan" method="post" action="../laporan/proses_tambah.php" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="field">
      <label for="lokasi">Lokasi</label>
      <div class="input-kunci">
        <input type="text" id="lokasi" value="Kamar <?= e($sewa['nomor_kamar']) ?> &middot; <?= e($sewa['kelas_kamar']) ?>" readonly>
        <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/><path d="M12 15v2"/></svg>
      </div>
    </div>

    <div class="field">
      <label for="kategori">Kategori</label>
      <div class="input-select">
        <select id="kategori" name="kategori" required>
          <option value="" disabled <?= empty($old['kategori']) ? 'selected' : '' ?>>Pilih Kategori</option>
          <?php foreach (KATEGORI_LAPORAN as $k): ?>
            <option value="<?= e($k) ?>" <?= ($old['kategori'] ?? '') === $k ? 'selected' : '' ?>><?= e($k) ?></option>
          <?php endforeach; ?>
        </select>
        <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
      </div>
    </div>

    <div class="field">
      <label for="judul">Judul Kerusakan</label>
      <input type="text" id="judul" name="judul" maxlength="80" placeholder="Contoh: Keran air wastafel patah" value="<?= e($old['judul'] ?? '') ?>" required>
    </div>

    <div class="field">
      <label for="deskripsi">Deskripsi</label>
      <textarea id="deskripsi" name="deskripsi" rows="5" maxlength="800" placeholder="Jelaskan detail kerusakan, waktu kejadian, atau kendala yang dialami..." required><?= e($old['deskripsi'] ?? '') ?></textarea>
    </div>

    <div class="field">
      <span class="label" id="labelFoto">Foto Kerusakan (Opsional)</span>
      <div class="unggah unggah--berkas">
        <span class="unggah-ikon" aria-hidden="true">
          <svg class="ikon" viewBox="0 0 24 24"><path d="M12 15V4M7 9l5-5 5 5"/><path d="M5 20h14"/></svg>
        </span>
        <span class="unggah-teks">Pilih foto kerusakan</span>
        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png" aria-labelledby="labelFoto">
        <span class="unggah-format">Format JPG atau PNG, maksimal 5 MB. Nama berkas yang dipilih tampil di sebelah tombol.</span>
      </div>
    </div>

    <div class="aksi-form">
      <a href="laporan-kerusakan.php" class="tombol-batal">Batal</a>
      <button type="submit" class="btn-utama">
        <svg class="ikon" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
        Kirim Laporan
      </button>
    </div>
  </form>

<?php fe_akhir_penghuni(); ?>
