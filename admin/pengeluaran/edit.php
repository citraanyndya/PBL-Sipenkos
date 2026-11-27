<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/validasi.php';
$judul_halaman = "Edit Pengeluaran";

$id = input_int($_GET, 'id') ?? 0;
$stmt = $pdo->prepare("SELECT * FROM pengeluaran_operasional WHERE id_pengeluaran = :id");
$stmt->execute(['id' => $id]);
$data = $stmt->fetch();
if (!$data) {
    set_flash('error', 'Data tidak ditemukan.');
    redirect('list.php');
}
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <a class="back-link" href="list.php">&larr; Kembali ke Daftar Pengeluaran</a>
        <h1 class="page-title">Edit Pengeluaran</h1>
    </div>
</div>
<div class="card card-pad form-card">
    <form method="post" action="proses_edit.php" class="form-grid">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $data['id_pengeluaran'] ?>">
        <div class="field">
            <label class="field-label" for="tgl_pengeluaran">Tanggal</label>
            <input class="input" type="date" id="tgl_pengeluaran" name="tgl_pengeluaran" value="<?= e($old['tgl_pengeluaran'] ?? $data['tgl_pengeluaran']) ?>" required>
        </div>
        <div class="field">
            <label class="field-label" for="kategori">Kategori</label>
            <select class="select" id="kategori" name="kategori">
                <?php foreach (KATEGORI_PENGELUARAN as $o): ?>
                <option value="<?= e($o) ?>" <?= ($old['kategori'] ?? $data['kategori']) === $o ? 'selected' : '' ?>><?= e($o) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label class="field-label" for="deskripsi_pengeluaran">Keterangan</label>
            <textarea class="textarea" id="deskripsi_pengeluaran" name="deskripsi_pengeluaran" rows="4" required><?= e($old['deskripsi_pengeluaran'] ?? $data['deskripsi_pengeluaran']) ?></textarea>
        </div>
        <div class="field">
            <label class="field-label" for="jml_biaya">Nominal (Rp)</label>
            <input class="input" type="number" id="jml_biaya" name="jml_biaya" value="<?= e($old['jml_biaya'] ?? $data['jml_biaya']) ?>" min="1" step="1" required>
        </div>

        <div class="modal-actions">
            <a class="btn btn-secondary btn-lg" href="list.php">Batal</a>
            <button type="submit" class="btn btn-primary btn-lg">Update</button>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
