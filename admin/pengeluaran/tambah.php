<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/validasi.php';
$judul_halaman = "Tambah Pengeluaran";
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <a class="back-link" href="list.php">&larr; Kembali ke Daftar Pengeluaran</a>
        <h1 class="page-title">Tambah Pengeluaran</h1>
    </div>
</div>
<div class="card card-pad form-card">
    <form method="post" action="proses_tambah.php" class="form-grid">
        <?= csrf_field() ?>
        <div class="field">
            <label class="field-label" for="tgl_pengeluaran">Tanggal</label>
            <input class="input" type="date" id="tgl_pengeluaran" name="tgl_pengeluaran" value="<?= e($old['tgl_pengeluaran'] ?? date('Y-m-d')) ?>" required>
        </div>
        <div class="field">
            <label class="field-label" for="kategori">Kategori</label>
            <select class="select" id="kategori" name="kategori">
                <?php foreach (KATEGORI_PENGELUARAN as $o): ?>
                <option value="<?= e($o) ?>" <?= ($old['kategori'] ?? '') === $o ? 'selected' : '' ?>><?= e($o) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label class="field-label" for="deskripsi_pengeluaran">Keterangan</label>
            <textarea class="textarea" id="deskripsi_pengeluaran" name="deskripsi_pengeluaran" rows="4" required><?= e($old['deskripsi_pengeluaran'] ?? '') ?></textarea>
        </div>
        <div class="field">
            <label class="field-label" for="jml_biaya">Nominal (Rp)</label>
            <input class="input" type="number" id="jml_biaya" name="jml_biaya" value="<?= e($old['jml_biaya'] ?? '') ?>" min="1" step="1" required>
        </div>

        <div class="modal-actions">
            <a class="btn btn-secondary btn-lg" href="list.php">Batal</a>
            <button type="submit" class="btn btn-primary btn-lg">Simpan</button>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
