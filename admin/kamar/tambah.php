<?php
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
$judul_halaman = "Tambah Kamar";

$tipe = $pdo->query("SELECT DISTINCT kelas_kamar FROM kamar ORDER BY kelas_kamar")->fetchAll(PDO::FETCH_COLUMN);
$fasilitas = $pdo->query("SELECT * FROM fasilitas ORDER BY kategori_fasilitas, nama_fasilitas")->fetchAll();
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <a class="back-link" href="list.php">&larr; Kelola Kamar</a>
        <h1 class="page-title">Tambah Kamar</h1>
    </div>
</div>
<div class="card card-pad form-card">
    <form method="post" action="proses_tambah.php" enctype="multipart/form-data" class="form-grid">
        <?= csrf_field() ?>
        <div class="field"><label class="field-label" for="nomor_kamar">Nomor Kamar</label>
            <input class="input" type="text" id="nomor_kamar" name="nomor_kamar" maxlength="10" required></div>
        <div class="field"><label class="field-label" for="kelas_kamar">Tipe Kamar</label>
            <input class="input" type="text" id="kelas_kamar" name="kelas_kamar" list="daftar-tipe" maxlength="50" required>
            <datalist id="daftar-tipe"><?php foreach ($tipe as $t): ?><option value="<?= e($t) ?>"><?php endforeach; ?></datalist></div>
        <div class="field"><label class="field-label" for="harga_sewa">Harga Sewa per Bulan (Rp)</label>
            <input class="input" type="number" id="harga_sewa" name="harga_sewa" min="1" required></div>
        <div class="field"><label class="field-label" for="status_kamar">Status</label>
            <select class="select" id="status_kamar" name="status_kamar"><option value="tersedia">Tersedia</option><option value="terisi">Terisi</option></select></div>
        <div class="field"><label class="field-label" for="foto_kamar">Foto Kamar (opsional)</label>
            <input class="input" type="file" id="foto_kamar" name="foto_kamar" accept="image/jpeg,image/png">
            <span class="field-hint">JPG/PNG, maksimal 5 MB.</span></div>
        <div class="field"><span class="field-label">Fasilitas</span>
            <div class="check-grid">
                <?php foreach ($fasilitas as $f): ?>
                <label class="check-item"><input type="checkbox" name="fasilitas[]" value="<?= (int) $f['id_fasilitas'] ?>"> <?= e($f['nama_fasilitas']) ?> <small>(<?= e($f['kategori_fasilitas']) ?>)</small></label>
                <?php endforeach; ?>
            </div></div>
        <div class="modal-actions"><a class="btn btn-secondary btn-lg" href="list.php">Batal</a><button type="submit" class="btn btn-primary btn-lg">Simpan</button></div>
    </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
