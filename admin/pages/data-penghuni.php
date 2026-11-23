<?php
$page_title = $page_title ?? 'Data Penghuni';
$page_id = $page_id ?? 'data-penghuni';
$page_module = 'penghuni';
$activeMenu = 'penghuni';
include __DIR__ . '/../includes/header.php';

$tampil_calon = $tampil_calon ?? false;
$buka_ktp = $buka_ktp ?? false;

$penghuni = [
    ['name' => 'Siti Nurhaliza', 'phone' => '0812–7788–1100', 'room' => '01', 'typeId' => 1, 'type' => 'Tipe 1 Non-AC, Kamar Mandi Luar', 'start' => '01 Sep 2026', 'status' => 'aktif', 'ktp' => 'ktp-siti-nurhaliza.jpg'],
    ['name' => 'Rina Fitria', 'phone' => '0813–9988–7766', 'room' => '03', 'typeId' => 3, 'type' => 'Tipe 3 AC, Kamar Mandi Luar', 'start' => '01 Okt 2026', 'status' => 'aktif', 'ktp' => ''],
    ['name' => 'Dinda Aulia', 'phone' => '0857–1122–3344', 'room' => '04', 'typeId' => 4, 'type' => 'Tipe 4 AC, Kamar Mandi Dalam', 'start' => '01 Okt 2026', 'status' => 'aktif', 'ktp' => ''],
    ['name' => 'Nana', 'phone' => '0896–2211–0099', 'room' => '07', 'typeId' => 4, 'type' => 'Tipe 4 AC, Kamar Mandi Dalam', 'start' => '01 Jul 2026', 'status' => 'aktif', 'ktp' => ''],
    ['name' => 'Nadia Putri', 'phone' => '0812–3456–7781', 'room' => '–', 'typeId' => 1, 'type' => 'Tipe 1 Non-AC, Kamar Mandi Luar', 'start' => '01 Nov 2026', 'status' => 'calon', 'ktp' => ''],
];

$status_badge = [
    'aktif' => ['Aktif', 'success'],
    'calon' => ['Calon', 'info'],
];

$app_data = ['residents' => $penghuni];
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Data Penghuni</h1>
        <p class="page-subtitle">Daftar seluruh penghuni aktif dan calon penyewa kos</p>
    </div>
</div>
<div class="list-section" data-list>
    <?php if ($tampil_calon) { ?>
    <div class="toolbar">
        <div class="chips" role="group" aria-label="Filter status"><button type="button" class="chip is-active" aria-pressed="true" data-filter="all" data-label="Semua" data-count>Semua</button><button type="button" class="chip" aria-pressed="false" data-filter="aktif" data-label="Aktif" data-count>Aktif</button><button type="button" class="chip" aria-pressed="false" data-filter="calon" data-label="Calon" data-count>Calon</button></div>
    <?php } else { ?>
    <div class="toolbar toolbar-end">
    <?php } ?>
        <label class="search"><i class="icon icon-search" aria-hidden="true"></i><span class="sr-only">Cari nama penghuni...</span><input type="search" placeholder="Cari nama penghuni..." value="" data-search autocomplete="off"><button type="button" class="search-clear" aria-label="Hapus pencarian"><i class="icon icon-x" aria-hidden="true"></i></button></label>
    </div>
    <div class="card">
        <div class="table-wrap">
            <table class="table table-penghuni">
                <thead>
                <tr>
                    <th>Penghuni</th>
                    <th>Kamar</th>
                    <th>Tipe Kamar</th>
                    <th class="ta-center">Mulai Sewa</th>
                    <?php if ($tampil_calon) { ?><th>Status</th><?php } ?>
                    <th class="ta-right">Dokumen</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($penghuni as $i => $p) {
                    if ($p['status'] == 'calon' && !$tampil_calon) { continue; } ?>
                    <tr data-item data-group="penghuni" data-status="<?php echo $p['status']; ?>" data-text="<?php echo strtolower($p['name'] . ' ' . $p['phone'] . ' ' . $p['room']); ?>">
                        <td>
                            <div class="person">
                                <span class="avatar"><?php echo inisial($p['name']); ?></span>
                                <div><p class="person-name"><?php echo $p['name']; ?></p><p class="person-sub"><?php echo $p['phone']; ?></p></div>
                            </div>
                        </td>
                        <td><?php echo $p['room']; ?></td>
                        <td class="nowrap"><?php echo $p['type']; ?></td>
                        <td class="ta-center nowrap"><?php echo $p['start']; ?></td>
                        <?php if ($tampil_calon) { ?>
                            <td data-badge><span class="badge badge-<?php echo $status_badge[$p['status']][1]; ?>"><span class="badge-dot"></span><span class="badge-label"><?php echo $status_badge[$p['status']][0]; ?></span></span></td>
                        <?php } ?>
                        <td class="ta-right"><button type="button" class="link" data-ktp="<?php echo $i; ?>">Lihat KTP</button></td>
                    </tr>
                <?php } ?>
                <tr class="table-empty" data-empty hidden><td colspan="6"><i class="icon icon-search" aria-hidden="true"></i>Penghuni tidak ditemukan.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal" id="modal-ktp" role="dialog" aria-modal="true" aria-labelledby="ktp-title"<?php if ($buka_ktp) { echo ' data-autoopen'; } ?> hidden>
    <div class="modal-dialog">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="ktp-title">Dokumen Identitas Penghuni</h2>
                <p class="modal-subtitle" data-f="subtitle">Siti Nurhaliza · Kamar 01</p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <div class="doc-layout">
            <div class="media media-zoom" data-f="media" data-zoom="../assets/images/ktp-siti-nurhaliza.jpg" data-zoom-alt="Foto KTP">
                <img src="../assets/images/ktp-siti-nurhaliza.jpg" alt="Foto KTP Siti Nurhaliza">
            </div>
            <div class="detail-list">
                <div class="detail-row"><span class="detail-label">Nama Lengkap</span><span class="detail-value" data-f="name">Siti Nurhaliza</span></div>
                <div class="detail-row"><span class="detail-label">Jenis Dokumen</span><span class="detail-value">KTP</span></div>
                <div class="detail-row"><span class="detail-label">No. HP</span><span class="detail-value" data-f="phone">0812–7788–1100</span></div>
                <div class="detail-row"><span class="detail-label">Kamar</span><span class="detail-value" data-f="room">01 · Tipe 1</span></div>
                <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value" data-f="status"><span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Terverifikasi</span></span></span></div>
            </div>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary btn-lg" data-modal-close>Tutup</button>
            <a class="btn btn-primary btn-lg" data-f="download" href="../assets/images/ktp-siti-nurhaliza.jpg" download="KTP-Siti-Nurhaliza.jpg">Unduh Dokumen</a>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
