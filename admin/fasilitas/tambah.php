<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/validasi.php';
$judul_halaman = "Tambah Fasilitas";
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <a class="back-link" href="list.php">&larr; Kembali ke Daftar Fasilitas</a>
        <h1 class="page-title">Tambah Fasilitas</h1>
    </div>
</div>
<div class="card card-pad form-card">
    <form method="post" action="proses_tambah.php" class="form-grid">
        <?= csrf_field() ?>
        <div class="field">
            <label class="field-label" for="nama_fasilitas">Nama Fasilitas</label>
            <input class="input" type="text" id="nama_fasilitas" name="nama_fasilitas" value="<?= e($old['nama_fasilitas'] ?? '') ?>" maxlength="100" required>
        </div>
        <div class="field">
            <label class="field-label" for="kategori_fasilitas">Kategori</label>
            <select class="select" id="kategori_fasilitas" name="kategori_fasilitas">
                <?php foreach (KATEGORI_FASILITAS as $o): ?>
                <option value="<?= e($o) ?>" <?= ($old['kategori_fasilitas'] ?? '') === $o ? 'selected' : '' ?>><?= e($o) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="modal-actions">
            <a class="btn btn-secondary btn-lg" href="list.php">Batal</a>
            <button type="submit" class="btn btn-primary btn-lg">Simpan</button>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
