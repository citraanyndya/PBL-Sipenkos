<?php
$page_title = $page_title ?? 'Laporan Kerusakan';
$page_id = $page_id ?? 'laporan-kerusakan';
$page_module = 'kerusakan';
$activeMenu = 'kerusakan';
include __DIR__ . '/../includes/header.php';

$banner_sukses = $banner_sukses ?? '';
$ubah_laporan = $ubah_laporan ?? [];

$laporan = [
    ['id' => 'K-1015', 'date' => '15 Okt 2026', 'name' => 'Nana', 'room' => '07', 'title' => 'Lampu kamar mandi mati', 'category' => 'Listrik & Lampu', 'status' => 'diproses', 'desc' => 'Lampu di kamar mandi tidak menyala sejak semalam, sudah dicoba ganti saklar tetapi tetap mati.', 'photo' => '', 'note' => 'Teknisi dijadwalkan datang besok pagi.'],
    ['id' => 'K-1001', 'date' => '01 Okt 2026', 'name' => 'Siti Nurhaliza', 'room' => '01', 'title' => 'Keran Kamar mandi bocor', 'category' => 'Air/Kamar mandi', 'status' => 'belum', 'desc' => 'Keran di kamar mandi menetes terus walaupun sudah ditutup rapat.', 'photo' => 'foto-keran-bocor.jpg', 'note' => ''],
    ['id' => 'K-0928', 'date' => '28 Sep 2026', 'name' => 'Rina Fitria', 'room' => '03', 'title' => 'Keran air bocor', 'category' => 'Air & Sanitasi', 'status' => 'selesai', 'desc' => 'Keran wastafel di dekat dapur bocor di bagian sambungan pipa.', 'photo' => '', 'note' => 'Sambungan pipa sudah diganti, silakan dicek kembali.'],
    ['id' => 'K-0920', 'date' => '20 Sep 2026', 'name' => 'Dinda Aulia', 'room' => '04', 'title' => 'Lampu kamar mati', 'category' => 'Listrik & Lampu', 'status' => 'selesai', 'desc' => 'Lampu utama kamar mati dan berkedip sebelum padam.', 'photo' => '', 'note' => 'Lampu sudah diganti dengan yang baru.'],
];
foreach ($laporan as $i => $l) {
    if (isset($ubah_laporan[$l['id']])) {
        $laporan[$i] = array_merge($l, $ubah_laporan[$l['id']]);
    }
}

$status_badge = [
    'belum' => ['Belum Dikerjakan', 'danger'],
    'diproses' => ['Diproses', 'info'],
    'selesai' => ['Selesai', 'success'],
];

$jumlahBaru = 0;
foreach ($laporan as $l) {
    if ($l['status'] == 'belum') {
        $jumlahBaru++;
    }
}

$app_data = ['reports' => $laporan];
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Kerusakan</h1>
        <p class="page-subtitle">Daftar laporan fasilitas rusak dari penghuni yang perlu ditindaklanjuti</p>
    </div>
</div>
<div data-banner-slot>
    <?php if ($banner_sukses != '') { ?>
    <div class="banner banner-success" role="status">
        <div class="banner-body"><i class="icon icon-circle-check" aria-hidden="true"></i><span><?php echo $banner_sukses; ?></span></div>
    </div>
    <?php } ?>
</div>
<?php if ($jumlahBaru > 0) { ?>
<div class="banner banner-danger banner-tight" data-alert-new>
    <div class="banner-body"><i class="icon icon-triangle-alert" aria-hidden="true"></i><span data-alert-count><?php echo $jumlahBaru; ?> laporan baru belum dikerjakan</span></div>
    <button type="button" class="btn btn-secondary btn-sm" data-show-new>Lihat Laporan</button>
</div>
<?php } ?>
<div class="list-section" data-list>
    <div class="toolbar">
        <div class="chips" role="group" aria-label="Filter status"><button type="button" class="chip is-active" aria-pressed="true" data-filter="all" data-label="Semua" data-count>Semua</button><button type="button" class="chip" aria-pressed="false" data-filter="belum" data-label="Belum Dikerjakan" data-count>Belum Dikerjakan</button><button type="button" class="chip" aria-pressed="false" data-filter="diproses" data-label="Diproses" data-count>Diproses</button><button type="button" class="chip" aria-pressed="false" data-filter="selesai" data-label="Selesai" data-count>Selesai</button></div>
        <label class="search search-xs"><i class="icon icon-search" aria-hidden="true"></i><span class="sr-only">Cari laporan...</span><input type="search" placeholder="Cari laporan..." value="" data-search autocomplete="off"><button type="button" class="search-clear" aria-label="Hapus pencarian"><i class="icon icon-x" aria-hidden="true"></i></button></label>
    </div>
    <div class="card">
        <div class="table-wrap">
            <table class="table table-kerusakan">
                <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pelapor</th>
                    <th>Rincian Laporan</th>
                    <th>Status</th>
                    <th class="ta-right">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($laporan as $l) { ?>
                    <tr data-item data-group="kerusakan" data-id="<?php echo $l['id']; ?>" data-status="<?php echo $l['status']; ?>" data-text="<?php echo htmlspecialchars(strtolower($l['name'] . ' kamar ' . $l['room'] . ' ' . $l['title'] . ' ' . $l['category'])); ?>">
                        <td class="nowrap"><?php echo $l['date']; ?></td>
                        <td>
                            <div class="person">
                                <span class="avatar"><?php echo inisial($l['name']); ?></span>
                                <div><p class="person-name"><?php echo $l['name']; ?></p><p class="person-sub">Kamar <?php echo $l['room']; ?></p></div>
                            </div>
                        </td>
                        <td><p class="cell-main"><?php echo $l['title']; ?></p><p class="cell-sub"><?php echo htmlspecialchars($l['category']); ?></p></td>
                        <td data-badge><span class="badge badge-<?php echo $status_badge[$l['status']][1]; ?>"><span class="badge-dot"></span><span class="badge-label"><?php echo $status_badge[$l['status']][0]; ?></span></span></td>
                        <td class="ta-right"><button type="button" class="btn btn-secondary btn-sm" data-report="<?php echo $l['id']; ?>">Detail</button></td>
                    </tr>
                <?php } ?>
                <tr class="table-empty" data-empty hidden><td colspan="5"><i class="icon icon-search" aria-hidden="true"></i>Tidak ada laporan yang cocok.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal" id="modal-kerusakan" role="dialog" aria-modal="true" aria-labelledby="kr-title" hidden>
    <div class="modal-dialog modal-xl">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="kr-title">Detail Laporan Kerusakan</h2>
                <p class="modal-subtitle" data-f="subtitle"></p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <div class="report-layout">
            <div>
                <div class="media" data-f="media" data-zoom-alt="Foto kerusakan"></div>
                <p class="media-caption">Foto dari penghuni</p>
            </div>
            <div>
                <div class="detail-list">
                    <div class="detail-row"><span class="detail-label">Kategori</span><span class="detail-value" data-f="category"></span></div>
                    <div class="detail-row"><span class="detail-label">Status Saat Ini</span><span class="detail-value" data-f="status"></span></div>
                </div>
                <div class="report-desc">
                    <p class="report-desc-label">Deskripsi</p>
                    <p class="report-desc-text" data-f="desc"></p>
                </div>
            </div>
        </div>
        <div class="modal-section">
            <span class="field-label" id="seg-label">Ubah Status</span>
            <div class="segmented" role="radiogroup" aria-labelledby="seg-label">
                <button type="button" role="radio" data-value="belum">Belum Dikerjakan</button>
                <button type="button" role="radio" data-value="diproses">Diproses</button>
                <button type="button" role="radio" data-value="selesai">Selesai</button>
            </div>
        </div>
        <div class="modal-section">
            <label class="field-label" for="kr-note">Catatan untuk Penghuni (opsional)</label>
            <textarea class="textarea" id="kr-note" rows="2" data-f="note" placeholder="Tulis catatan untuk penghuni..."></textarea>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary btn-lg" data-modal-close>Batal</button>
            <button type="button" class="btn btn-primary btn-lg" data-f="save">Simpan Status</button>
        </div>
    </div>
</div>
<div class="modal" id="modal-kerusakan-view" role="dialog" aria-modal="true" aria-labelledby="krv-title" hidden>
    <div class="modal-dialog modal-xl">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="krv-title">Detail Laporan Kerusakan</h2>
                <p class="modal-subtitle" data-f="subtitle"></p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <div class="report-layout">
            <div>
                <div class="media" data-f="media" data-zoom-alt="Foto kerusakan"></div>
                <p class="media-caption">Foto dari penghuni</p>
            </div>
            <div>
                <div class="detail-list">
                    <div class="detail-row"><span class="detail-label">Kategori</span><span class="detail-value" data-f="category"></span></div>
                    <div class="detail-row"><span class="detail-label">Status Saat Ini</span><span class="detail-value" data-f="status"></span></div>
                </div>
                <div class="report-desc">
                    <p class="report-desc-label">Deskripsi</p>
                    <p class="report-desc-text" data-f="desc"></p>
                </div>
            </div>
        </div>
        <div class="modal-section">
            <span class="field-label">Catatan untuk Penghuni (opsional)</span>
            <div class="readonly-box" data-f="note"></div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
