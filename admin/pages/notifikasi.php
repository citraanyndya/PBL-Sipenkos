<?php
$page_title = 'Notifikasi';
$page_id = 'notifikasi';
$page_module = 'notifikasi';
$activeMenu = '';
include __DIR__ . '/../includes/header.php';

$jenis = [
    'reservasi' => ['Reservasi', 'info', 'blue', 'calendar-check'],
    'pembayaran' => ['Pembayaran', 'warning', 'amber', 'credit-card'],
    'kerusakan' => ['Kerusakan', 'danger', 'red', 'wrench'],
];

$notifikasi = [
    ['type' => 'reservasi', 'text' => 'Nadia Putri mengajukan reservasi Tipe 1 Non-AC, Kamar Mandi Luar', 'time' => '15 Okt 2026 · 10:30', 'href' => 'data-reservasi.php', 'unread' => true],
    ['type' => 'kerusakan', 'text' => 'Nana melaporkan kerusakan: Lampu kamar mandi mati (Kamar 07)', 'time' => '15 Okt 2026 · 08:05', 'href' => 'laporan-kerusakan.php', 'unread' => true],
    ['type' => 'pembayaran', 'text' => 'Siti Nurhaliza mengunggah bukti transfer tagihan Oktober 2026', 'time' => '02 Okt 2026 · 14:20', 'href' => 'detail-bukti-transfer.php', 'unread' => false],
    ['type' => 'kerusakan', 'text' => 'Siti Nurhaliza melaporkan kerusakan: AC kurang dingin (Kamar 01)', 'time' => '01 Okt 2026 · 14:15', 'href' => 'laporan-kerusakan.php', 'unread' => false],
    ['type' => 'pembayaran', 'text' => 'Rina Fitria mengunggah bukti transfer tagihan Oktober 2026', 'time' => '01 Okt 2026 · 09:00', 'href' => 'verifikasi-pembayaran.php', 'unread' => false],
];
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Notifikasi</h1>
        <p class="page-subtitle">Riwayat pemberitahuan aktivitas dan pengajuan terbaru dari penghuni</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-secondary btn-sm" data-mark-all>Tandai Semua Dibaca</button>
    </div>
</div>
<div class="list-section" data-list>
    <div class="toolbar">
        <div class="chips" role="group" aria-label="Filter status"><button type="button" class="chip is-active" aria-pressed="true" data-filter="all" data-label="Semua" data-count>Semua</button><button type="button" class="chip" aria-pressed="false" data-filter="unread" data-label="Belum Dibaca" data-count>Belum Dibaca</button><button type="button" class="chip" aria-pressed="false" data-filter="reservasi" data-label="Reservasi">Reservasi</button><button type="button" class="chip" aria-pressed="false" data-filter="pembayaran" data-label="Pembayaran">Pembayaran</button><button type="button" class="chip" aria-pressed="false" data-filter="kerusakan" data-label="Kerusakan">Kerusakan</button></div>
    </div>
    <div class="card notif-list">
        <?php foreach ($notifikasi as $n) {
            $j = $jenis[$n['type']]; ?>
            <div class="notif-item<?php if ($n['unread']) { echo ' is-unread'; } ?>" role="link" tabindex="0" data-item
                 data-status="<?php echo $n['type']; ?><?php if ($n['unread']) { echo ' unread'; } ?>" data-href="<?php echo $n['href']; ?>">
                <span class="icon-box tone-<?php echo $j[2]; ?>"><i class="icon icon-<?php echo $j[3]; ?>" aria-hidden="true"></i></span>
                <div class="notif-body">
                    <p class="notif-text"><?php echo $n['text']; ?></p>
                    <p class="notif-time"><i class="icon icon-clock" aria-hidden="true"></i><?php echo $n['time']; ?></p>
                </div>
                <span class="badge badge-<?php echo $j[1]; ?>"><span class="badge-dot"></span><span class="badge-label"><?php echo $j[0]; ?></span></span>
                <span class="notif-unread-dot" aria-label="<?php echo $n['unread'] ? 'Belum dibaca' : 'Sudah dibaca'; ?>"></span>
            </div>
        <?php } ?>
        <div class="notif-empty" data-empty hidden>Tidak ada notifikasi pada kategori ini.</div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
