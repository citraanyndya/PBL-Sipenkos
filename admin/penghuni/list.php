<?php
require_once __DIR__ . '/../../includes/koneksi.php';
require_once __DIR__ . '/../../includes/auth.php';
wajib_role('pengelola');
$judul_halaman = "Data Penghuni";

$status = input_str($_GET, 'status', 10);
$stmt = $pdo->query(
    "SELECT g.id_penghuni, g.nik, g.nama_lengkap, g.no_hp, g.kontak_darurat, g.nama_kontak_darurat, g.hubungan_kontak_darurat, g.foto_ktp,
            a.nomor_kamar, a.kelas_kamar, a.tgl_mulai, a.tgl_selesai
       FROM penghuni g
       LEFT JOIN LATERAL (
            SELECT k.nomor_kamar, k.kelas_kamar, p.tgl_mulai, " . sql_selesai('p') . " AS tgl_selesai
              FROM pemesanan p JOIN kamar k ON k.id_kamar = p.id_kamar
             WHERE p.id_penghuni = g.id_penghuni AND p.status_pesanan = 'diterima' AND " . sql_selesai('p') . " >= CURRENT_DATE
             ORDER BY " . sql_selesai('p') . " DESC LIMIT 1
       ) a ON TRUE
      ORDER BY g.nama_lengkap"
);
$daftar = array_filter($stmt->fetchAll(), function ($r) use ($status) {
    return $status === '' || ($status === 'aktif') === ($r['nomor_kamar'] !== null);
});
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Data Penghuni</h1>
        <p class="page-subtitle">Daftar seluruh penghuni aktif dan calon penyewa kos</p>
    </div>
</div>
<div class="toolbar"><?= a_chips(['' => 'Semua', 'aktif' => 'Aktif', 'calon' => 'Calon'], (string) $status) ?></div>
<div class="card">
    <div class="table-wrap">
        <table class="table table-penghuni">
            <thead><tr><th>Penghuni</th><th>Kontak</th><th>Kamar</th><th>Mulai Sewa</th><th>Status</th><th>Dokumen</th></tr></thead>
            <tbody>
            <?php if (empty($daftar)): ?>
                <tr class="table-empty"><td colspan="6"><?= a_ikon('search') ?>Tidak ada data.</td></tr>
            <?php else: foreach ($daftar as $r): ?>
                <tr>
                    <td><?= a_orang($r['nama_lengkap'], 'NIK ' . mask_nik($r['nik'])) ?></td>
                    <td class="cell-wrap"><?= e($r['no_hp']) ?><span class="col-sub">Darurat: <?= $r['kontak_darurat'] ? e(($r['nama_kontak_darurat'] ?: '?') . ' (' . ($r['hubungan_kontak_darurat'] ?: '?') . ') ' . $r['kontak_darurat']) : '-' ?></span></td>
                    <td class="cell-wrap"><?= $r['nomor_kamar'] ? e($r['nomor_kamar']) . '<span class="col-sub">' . e($r['kelas_kamar']) . '</span>' : '-' ?></td>
                    <td class="nowrap"><?= $r['tgl_mulai'] ? tgl_indo($r['tgl_mulai']) : '-' ?></td>
                    <td><?= a_badge($r['nomor_kamar'] ? 'aktif' : 'calon') ?></td>
                    <td><?= $r['foto_ktp'] ? '<a class="link" href="' . e(url_berkas('ktp', $r['foto_ktp'])) . '" target="_blank" rel="noopener">Lihat KTP</a>' : '-' ?></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
