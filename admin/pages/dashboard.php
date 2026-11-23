<?php
$page_title = 'Dashboard';
$page_id = 'dashboard';
$activeMenu = 'dashboard';
include __DIR__ . '/../includes/header.php';

$totalKamar = 20;
$kamarTerisi = 14;
$kamarKosong = $totalKamar - $kamarTerisi;
$reservasiBaru = 1;

$tugas = [
    ['tone' => 'blue', 'icon' => 'calendar-check', 'judul' => 'Reservasi baru dari Nadia Putri', 'sub' => 'Tipe 1 Non-AC, Kamar Mandi Luar · 15 Okt 2026', 'link' => 'data-reservasi.php', 'tombol' => 'Tinjau'],
    ['tone' => 'amber', 'icon' => 'credit-card', 'judul' => 'Bukti transfer Siti Nurhaliza', 'sub' => 'Tagihan Oktober 2026 · Rp 500.000', 'link' => 'detail-bukti-transfer.php', 'tombol' => 'Verifikasi'],
    ['tone' => 'red', 'icon' => 'wrench', 'judul' => 'Laporan kerusakan Kamar 01', 'sub' => 'AC kurang dingin · 01 Okt 2026', 'link' => 'detail-laporan-kerusakan.php', 'tombol' => 'Detail'],
];

$okupansi = [
    ['nama' => 'Tipe 1 Non-AC, KM Luar', 'terisi' => 6, 'total' => 7],
    ['nama' => 'Tipe 2 Non-AC, KM Dalam', 'terisi' => 3, 'total' => 5],
    ['nama' => 'Tipe 3 AC, KM Luar', 'terisi' => 3, 'total' => 3],
    ['nama' => 'Tipe 4 AC, KM Dalam', 'terisi' => 2, 'total' => 5],
];
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Selamat datang, Admin</h1>
        <p class="page-subtitle">Ringkasan aktivitas Kos Bu Nining · Oktober 2026</p>
    </div>
</div>
<div class="banner banner-warning" id="dashboard-banner">
    <div class="banner-body">
        <i class="icon icon-triangle-alert" aria-hidden="true"></i>
        <span>1 bukti pembayaran dari Siti Nurhaliza menunggu verifikasi</span>
    </div>
    <a href="detail-bukti-transfer.php" class="btn btn-primary btn-md">Verifikasi Sekarang</a>
</div>
<section class="stats stats-4" aria-label="Statistik kamar">
    <div class="card stat">
        <span class="icon-box tone-blue"><i class="icon icon-bed-double" aria-hidden="true"></i></span>
        <div><p class="stat-label">Total Kamar</p><p class="stat-value"><?php echo $totalKamar; ?></p></div>
    </div>
    <div class="card stat">
        <span class="icon-box tone-green"><i class="icon icon-users" aria-hidden="true"></i></span>
        <div><p class="stat-label">Kamar Terisi</p><p class="stat-value"><?php echo $kamarTerisi; ?></p></div>
    </div>
    <div class="card stat">
        <span class="icon-box tone-amber"><i class="icon icon-door-open" aria-hidden="true"></i></span>
        <div><p class="stat-label">Kamar Kosong</p><p class="stat-value"><?php echo $kamarKosong; ?></p></div>
    </div>
    <div class="card stat">
        <span class="icon-box tone-blue"><i class="icon icon-calendar-check" aria-hidden="true"></i></span>
        <div><p class="stat-label">Reservasi Baru</p><p class="stat-value"><?php echo $reservasiBaru; ?></p></div>
    </div>
</section>
<section class="grid-2">
    <div class="card dashboard-card-pad">
        <div class="card-head">
            <h2 class="card-title">Perlu Ditindaklanjuti</h2>
            <span class="badge badge-danger"><span class="badge-dot"></span><span class="badge-label"><?php echo count($tugas); ?> tugas</span></span>
        </div>
        <div class="task-list">
            <?php foreach ($tugas as $t) { ?>
                <div class="task-item">
                    <span class="icon-box icon-box-sm tone-<?php echo $t['tone']; ?>"><i class="icon icon-<?php echo $t['icon']; ?>" aria-hidden="true"></i></span>
                    <div class="task-body">
                        <p class="task-title"><?php echo $t['judul']; ?></p>
                        <p class="task-sub"><?php echo $t['sub']; ?></p>
                    </div>
                    <a href="<?php echo $t['link']; ?>" class="btn btn-secondary btn-sm"><?php echo $t['tombol']; ?></a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="card dashboard-card-pad occupancy-card">
        <div class="card-head">
            <h2 class="card-title">Okupansi per Tipe Kamar</h2>
            <a href="monitor-kamar.php" class="btn btn-ghost btn-sm">Lihat Semua</a>
        </div>
        <div class="occupancy">
            <?php foreach ($okupansi as $o) {
                $persen = round($o['terisi'] / $o['total'] * 100); ?>
                <div class="occ-item">
                    <div class="occ-head">
                        <span class="occ-name"><?php echo $o['nama']; ?></span>
                        <span class="occ-count"><?php echo $o['terisi']; ?>/<?php echo $o['total']; ?> terisi</span>
                    </div>
                    <div class="progress" role="progressbar" aria-valuenow="<?php echo $persen; ?>" aria-valuemin="0" aria-valuemax="100" aria-label="<?php echo $o['nama']; ?>">
                        <div class="progress-fill<?php if ($persen == 100) { echo ' is-full'; } ?>" data-width="<?php echo $persen; ?>"></div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
