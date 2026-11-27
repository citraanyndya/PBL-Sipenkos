<?php
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/validasi.php';
$judul_halaman = "Edit Fasilitas";

$id = input_int($_GET, 'id') ?? 0;
$stmt = $pdo->prepare("SELECT * FROM fasilitas WHERE id_fasilitas = :id");
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
        <a class="back-link" href="list.php">&larr; Kembali ke Daftar Fasilitas</a>
        <h1 class="page-title">Edit Fasilitas</h1>
    </div>
</div>
<div class="card card-pad form-card">
    <form method="post" action="proses_edit.php" class="form-grid">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $data['id_fasilitas'] ?>">
        <div class="field">
            <label class="field-label" for="nama_fasilitas">Nama Fasilitas</label>
            <input class="input" type="text" id="nama_fasilitas" name="nama_fasilitas" value="<?= e($old['nama_fasilitas'] ?? $data['nama_fasilitas']) ?>" maxlength="100" required>
        </div>
        <div class="field">
            <label class="field-label" for="kategori_fasilitas">Kategori</label>
            <select class="select" id="kategori_fasilitas" name="kategori_fasilitas">
                <?php foreach (KATEGORI_FASILITAS as $o): ?>
                <option value="<?= e($o) ?>" <?= ($old['kategori_fasilitas'] ?? $data['kategori_fasilitas']) === $o ? 'selected' : '' ?>><?= e($o) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="modal-actions">
            <a class="btn btn-secondary btn-lg" href="list.php">Batal</a>
            <button type="submit" class="btn btn-primary btn-lg">Update</button>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
