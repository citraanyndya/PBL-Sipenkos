<?php
// Fitur 09 - Laporan Kerusakan: detail satu laporan + ubah status (sisi pengelola).
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/fe.php';   // fe_pecah_laporan()
$judul_halaman = "Detail Laporan Kerusakan";

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare(
    "SELECT l.id_laporan, l.tgl_laporan, l.deskripsi_laporan, l.foto_kerusakan, l.status_laporan,
            g.nama_lengkap, g.no_hp, k.nomor_kamar, k.kelas_kamar, p.nama_pengelola
       FROM laporan_kerusakan l
       JOIN penghuni g ON g.id_penghuni = l.id_penghuni
       JOIN kamar k ON k.id_kamar = l.id_kamar
       LEFT JOIN pengelola p ON p.id_pengelola = l.id_pengelola
      WHERE l.id_laporan = :i"
);
$stmt->execute(['i' => $id]);
$lap = $stmt->fetch();

require_once __DIR__ . '/../../includes/header.php';

if (!$lap): ?>
<div class="page-header">
    <div>
        <a class="back-link" href="list.php">&larr; Laporan Kerusakan</a>
        <h1 class="page-title">Laporan tidak ditemukan</h1>
    </div>
</div>
<div class="card card-pad">
    <p class="table-note">Laporan dengan nomor tersebut tidak ada. Mungkin sudah dihapus.</p>
</div>
<?php else:
    [$kategori, $judul, $isi] = fe_pecah_laporan($lap['deskripsi_laporan']);
    $berikut = ['baru' => ['diproses', 'Mulai Proses'], 'diproses' => ['selesai', 'Tandai Selesai']][$lap['status_laporan']] ?? null;
?>
<div class="page-header">
    <div>
        <a class="back-link" href="list.php">&larr; Laporan Kerusakan</a>
        <h1 class="page-title">Laporan #<?= (int) $lap['id_laporan'] ?> &middot; <?= e($judul) ?></h1>
        <p class="page-subtitle"><?= e($lap['nama_lengkap']) ?> &middot; Kamar <?= e($lap['nomor_kamar']) ?> (<?= e($lap['kelas_kamar']) ?>)</p>
    </div>
    <div class="page-actions"><?= a_badge($lap['status_laporan']) ?></div>
</div>

<div class="card detail-card">
    <div class="detail-list">
        <div class="detail-row"><span class="detail-label">Kategori</span><span class="detail-value"><?= e($kategori) ?></span></div>
        <div class="detail-row"><span class="detail-label">Penghuni</span><span class="detail-value"><?= e($lap['nama_lengkap']) ?> (<?= e($lap['no_hp']) ?>)</span></div>
        <div class="detail-row"><span class="detail-label">Kamar</span><span class="detail-value"><?= e($lap['kelas_kamar']) ?> (Kamar <?= e($lap['nomor_kamar']) ?>)</span></div>
        <div class="detail-row"><span class="detail-label">Tanggal lapor</span><span class="detail-value"><?= tgl_indo($lap['tgl_laporan'], true) ?></span></div>
        <div class="detail-row"><span class="detail-label">Ditangani oleh</span><span class="detail-value"><?= $lap['nama_pengelola'] ? e($lap['nama_pengelola']) : '- (belum ada yang menangani)' ?></span></div>
        <div class="detail-row"><span class="detail-label">Keterangan penghuni</span><span class="detail-value"><?= nl2br(e($isi)) ?></span></div>
        <div class="detail-row"><span class="detail-label">Foto kerusakan</span><span class="detail-value">
            <?php if ($lap['foto_kerusakan']): ?>
                <a class="link" href="<?= e(url_berkas('kerusakan', $lap['foto_kerusakan'])) ?>" target="_blank" rel="noopener">Buka foto ukuran penuh</a>
                <img src="<?= e(url_berkas('kerusakan', $lap['foto_kerusakan'])) ?>" alt="Foto kerusakan dari <?= e($lap['nama_lengkap']) ?>" style="display:block;max-width:360px;margin-top:10px;border-radius:8px">
            <?php else: ?>
                - (penghuni tidak melampirkan foto)
            <?php endif; ?>
        </span></div>
    </div>
</div>

<div class="card card-pad card-section">
    <h3 class="card-title">Tindak Lanjut</h3>
    <?php if ($berikut): ?>
        <p class="table-note">Status berjalan satu arah: Belum Dikerjakan &rarr; Diproses &rarr; Selesai.</p>
        <form method="post" action="proses_status.php" class="form-konfirmasi" data-konfirmasi="<?= e($berikut[1]) ?> untuk laporan dari <?= e($lap['nama_lengkap']) ?>?" data-ok="Ya, Lanjut" style="margin-top:12px">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) $lap['id_laporan'] ?>">
            <input type="hidden" name="status" value="<?= e($berikut[0]) ?>">
            <button type="submit" class="btn btn-primary btn-md"><?= e($berikut[1]) ?></button>
        </form>
    <?php else: ?>
        <p class="table-note">Laporan ini sudah selesai. Tidak ada tindakan lanjutan.</p>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
