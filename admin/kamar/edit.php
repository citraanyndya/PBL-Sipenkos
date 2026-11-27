<?php
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
$judul_halaman = "Edit Kamar";

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM kamar WHERE id_kamar = :id");
$stmt->execute(['id' => $id]);
$kamar = $stmt->fetch();
if (!$kamar) {
    set_flash('error', 'Kamar tidak ditemukan.');
    redirect('list.php');
}

$fasilitas = $pdo->query("SELECT * FROM fasilitas ORDER BY kategori_fasilitas, nama_fasilitas")->fetchAll();
$dipilih = $pdo->prepare("SELECT id_fasilitas FROM kamar_fasilitas WHERE id_kamar = :id");
$dipilih->execute(['id' => $id]);
$dipilih = array_map('intval', $dipilih->fetchAll(PDO::FETCH_COLUMN));
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <a class="back-link" href="list.php">&larr; Kelola Kamar</a>
        <h1 class="page-title">Edit Kamar <?= e($kamar['nomor_kamar']) ?></h1>
    </div>
</div>
<div class="card card-pad form-card">
    <form method="post" action="proses_edit.php" enctype="multipart/form-data" class="form-grid">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $kamar['id_kamar'] ?>">
        <div class="field"><label class="field-label" for="nomor_kamar">Nomor Kamar</label>
            <input class="input" type="text" id="nomor_kamar" name="nomor_kamar" maxlength="10" value="<?= e($kamar['nomor_kamar']) ?>" required></div>
        <div class="field"><label class="field-label" for="kelas_kamar">Tipe Kamar</label>
            <input class="input" type="text" id="kelas_kamar" name="kelas_kamar" maxlength="50" value="<?= e($kamar['kelas_kamar']) ?>" required></div>
        <div class="field"><label class="field-label" for="harga_sewa">Harga Sewa per Bulan (Rp)</label>
            <input class="input" type="number" id="harga_sewa" name="harga_sewa" min="1" value="<?= (int) $kamar['harga_sewa'] ?>" required>
            <span class="field-hint">Perubahan harga hanya berlaku untuk pemesanan baru. Pemesanan yang sudah ada tetap memakai harga saat dipesan, dan perubahan dicatat di riwayat harga.</span></div>
        <div class="field"><label class="field-label" for="status_kamar">Status</label>
            <select class="select" id="status_kamar" name="status_kamar">
                <?php foreach (['tersedia' => 'Tersedia', 'ditahan' => 'Ditahan', 'terisi' => 'Terisi'] as $v => $l): ?>
                <option value="<?= $v ?>" <?= $kamar['status_kamar'] === $v ? 'selected' : '' ?>><?= $l ?></option>
                <?php endforeach; ?>
            </select></div>
        <div class="field"><label class="field-label" for="foto_kamar">Ganti Foto Kamar (opsional)</label>
            <?php if ($kamar['foto_kamar']): ?><img class="photo-small" src="<?= e(url_foto_kamar($kamar['foto_kamar'])) ?>" alt="Foto kamar"><?php endif; ?>
            <input class="input" type="file" id="foto_kamar" name="foto_kamar" accept="image/jpeg,image/png"></div>
        <div class="field"><span class="field-label">Fasilitas</span>
            <div class="check-grid">
                <?php foreach ($fasilitas as $f): ?>
                <label class="check-item"><input type="checkbox" name="fasilitas[]" value="<?= (int) $f['id_fasilitas'] ?>" <?= in_array((int) $f['id_fasilitas'], $dipilih, true) ? 'checked' : '' ?>> <?= e($f['nama_fasilitas']) ?> <small>(<?= e($f['kategori_fasilitas']) ?>)</small></label>
                <?php endforeach; ?>
            </div></div>
        <div class="modal-actions"><a class="btn btn-secondary btn-lg" href="list.php">Batal</a><button type="submit" class="btn btn-primary btn-lg">Update</button></div>
    </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
