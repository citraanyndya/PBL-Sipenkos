<?php
require_once __DIR__ . '/../includes/auth.php';
wajib_role('pengelola');
require_once __DIR__ . '/../includes/koneksi.php';
$judul_halaman = "Dasbor Admin";

$k = $pdo->query(
    "SELECT COUNT(*) AS total,
            COUNT(*) FILTER (WHERE status_kamar = 'terisi') AS terisi,
            COUNT(*) FILTER (WHERE status_kamar = 'ditahan') AS ditahan,
            COUNT(*) FILTER (WHERE status_kamar = 'tersedia') AS kosong FROM kamar"
)->fetch();
$c = $pdo->query(
    "SELECT (SELECT COUNT(*) FROM pemesanan WHERE status_pesanan = 'menunggu_verifikasi') AS reservasi_baru,
            (SELECT COUNT(*) FROM pembayaran WHERE status_bayar = 'menunggu') AS bukti_menunggu,
            (SELECT COUNT(*) FROM laporan_kerusakan WHERE status_laporan = 'baru') AS laporan_baru"
)->fetch();
$okupansi = $pdo->query(
    "SELECT kelas_kamar, COUNT(*) AS total, COUNT(*) FILTER (WHERE status_kamar = 'terisi') AS terisi
       FROM kamar GROUP BY kelas_kamar ORDER BY MIN(harga_sewa)"
)->fetchAll();

// "Perlu ditindaklanjuti": item terbaru dari tiga antrean kerja admin.
$tugas = [];
foreach ($pdo->query(
    "SELECT p.id_pemesanan, p.tgl_pemesanan, g.nama_lengkap, k.kelas_kamar FROM pemesanan p
       JOIN penghuni g ON g.id_penghuni = p.id_penghuni JOIN kamar k ON k.id_kamar = p.id_kamar
      WHERE p.status_pesanan = 'menunggu_verifikasi' AND NOT EXISTS (SELECT 1 FROM pembayaran b WHERE b.id_pemesanan = p.id_pemesanan AND b.status_bayar = 'menunggu')
      ORDER BY p.tgl_pemesanan DESC LIMIT 3"
) as $r) {
    $tugas[] = ['ikon' => 'kalender-cek', 'warna' => '', 'judul' => 'Reservasi baru dari ' . $r['nama_lengkap'], 'sub' => $r['kelas_kamar'] . ' · ' . tgl_indo($r['tgl_pemesanan']),
                'aksi' => 'Tinjau', 'link' => 'pemesanan/detail.php?id=' . $r['id_pemesanan'], 'waktu' => $r['tgl_pemesanan']];
}
foreach ($pdo->query(
    "SELECT p.id_pemesanan, b.tgl_bayar, b.jml_bayar, g.nama_lengkap FROM pembayaran b
       JOIN pemesanan p ON p.id_pemesanan = b.id_pemesanan JOIN penghuni g ON g.id_penghuni = p.id_penghuni
      WHERE b.status_bayar = 'menunggu' ORDER BY b.tgl_bayar DESC LIMIT 3"
) as $r) {
    $tugas[] = ['ikon' => 'kartu', 'warna' => 'kuning', 'judul' => 'Bukti transfer ' . $r['nama_lengkap'], 'sub' => rupiah($r['jml_bayar']) . ' · ' . tgl_indo($r['tgl_bayar']),
                'aksi' => 'Verifikasi', 'link' => 'pemesanan/detail.php?id=' . $r['id_pemesanan'], 'waktu' => $r['tgl_bayar']];
}
foreach ($pdo->query(
    "SELECT l.id_laporan, l.tgl_laporan, l.deskripsi_laporan, k.nomor_kamar FROM laporan_kerusakan l
       JOIN kamar k ON k.id_kamar = l.id_kamar WHERE l.status_laporan = 'baru' ORDER BY l.tgl_laporan DESC LIMIT 3"
) as $r) {
    $tugas[] = ['ikon' => 'kunci', 'warna' => 'merah', 'judul' => 'Laporan kerusakan Kamar ' . $r['nomor_kamar'], 'sub' => mb_strimwidth($r['deskripsi_laporan'], 0, 40, '...') . ' · ' . tgl_indo($r['tgl_laporan']),
                'aksi' => 'Detail', 'link' => 'laporan/detail.php?id=' . $r['id_laporan'], 'waktu' => $r['tgl_laporan']];
}
usort($tugas, fn($a, $b) => strtotime($b['waktu']) <=> strtotime($a['waktu']));

$bukti = $pdo->query(
    "SELECT g.nama_lengkap FROM pembayaran b JOIN pemesanan p ON p.id_pemesanan = b.id_pemesanan JOIN penghuni g ON g.id_penghuni = p.id_penghuni
      WHERE b.status_bayar = 'menunggu' ORDER BY b.tgl_bayar DESC LIMIT 1"
)->fetchColumn();

require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Selamat datang, Admin</h1>
        <p class="page-subtitle">Ringkasan aktivitas Kos Bu Nining · <?= BULAN_INDO[(int) date('n')] ?> <?= date('Y') ?></p>
    </div>
</div>
<?php if ($c['bukti_menunggu'] > 0): ?>
<div class="banner banner-warning">
    <div class="banner-body">
        <?= a_ikon('triangle-alert') ?>
        <span><?= (int) $c['bukti_menunggu'] ?> bukti pembayaran<?= $c['bukti_menunggu'] == 1 ? ' dari ' . e($bukti) : '' ?> menunggu verifikasi</span>
    </div>
    <a href="pembayaran/list.php?status=menunggu" class="btn btn-primary btn-md">Verifikasi Sekarang</a>
</div>
<?php endif; ?>
<section class="stats stats-4" aria-label="Statistik kamar">
    <div class="card stat">
        <span class="icon-box tone-blue"><?= a_ikon('bed-double') ?></span>
        <div><p class="stat-label">Total Kamar</p><p class="stat-value"><?= (int) $k['total'] ?></p></div>
    </div>
    <div class="card stat">
        <span class="icon-box tone-green"><?= a_ikon('users') ?></span>
        <div><p class="stat-label">Kamar Terisi</p><p class="stat-value"><?= (int) $k['terisi'] ?></p></div>
    </div>
    <div class="card stat">
        <span class="icon-box tone-amber"><?= a_ikon('door-open') ?></span>
        <div><p class="stat-label">Kamar Kosong</p><p class="stat-value"><?= (int) $k['kosong'] ?></p></div>
    </div>
    <div class="card stat">
        <span class="icon-box tone-blue"><?= a_ikon('calendar-check') ?></span>
        <div><p class="stat-label">Reservasi Baru</p><p class="stat-value"><?= (int) $c['reservasi_baru'] ?></p></div>
    </div>
</section>
<section class="grid-2">
    <div class="card dashboard-card-pad">
        <div class="card-head">
            <h2 class="card-title">Perlu Ditindaklanjuti</h2>
            <?php $jml = $c['reservasi_baru'] + $c['bukti_menunggu'] + $c['laporan_baru']; ?>
            <?php if ($jml > 0): ?><span class="badge badge-danger"><span class="badge-dot"></span><span class="badge-label"><?= (int) $jml ?> tugas</span></span><?php endif; ?>
        </div>
        <div class="task-list">
            <?php if (empty($tugas)): ?><p class="task-empty">Tidak ada tugas tertunda.</p><?php endif; ?>
            <?php
            $tone = ['' => ['blue', 'calendar-check'], 'kuning' => ['amber', 'credit-card'], 'merah' => ['red', 'wrench']];
            foreach (array_slice($tugas, 0, 6) as $t): [$tn, $ik] = $tone[$t['warna']] ?? $tone['']; ?>
            <div class="task-item">
                <span class="icon-box icon-box-sm tone-<?= $tn ?>"><?= a_ikon($ik) ?></span>
                <div class="task-body"><p class="task-title"><?= e($t['judul']) ?></p><p class="task-sub"><?= e($t['sub']) ?></p></div>
                <a class="btn btn-secondary btn-sm" href="<?= e($t['link']) ?>"><?= e($t['aksi']) ?></a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="card dashboard-card-pad occupancy-card">
        <div class="card-head">
            <h2 class="card-title">Okupansi per Tipe Kamar</h2>
            <a href="kamar/monitor.php" class="link">Lihat Semua</a>
        </div>
        <div class="occupancy">
            <?php foreach ($okupansi as $o): $persen = $o['total'] ? round($o['terisi'] / $o['total'] * 100) : 0; ?>
            <div class="occ-item">
                <div class="occ-head"><span class="occ-name"><?= e($o['kelas_kamar']) ?></span><span class="occ-count"><?= (int) $o['terisi'] ?>/<?= (int) $o['total'] ?> terisi</span></div>
                <div class="progress"><div class="progress-fill<?= $persen >= 100 ? ' is-full' : '' ?>" style="width: <?= $persen ?>%"></div></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
