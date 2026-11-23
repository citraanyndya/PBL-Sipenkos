<?php
$page_title = $page_title ?? 'Verifikasi Pembayaran';
$page_id = $page_id ?? 'verifikasi-pembayaran';
$page_module = 'verifikasi';
$activeMenu = 'verifikasi';
include __DIR__ . '/../includes/header.php';

$banner_sukses = $banner_sukses ?? '';
$ubah_status = $ubah_status ?? [];
$buka_bukti = $buka_bukti ?? '';

$pembayaran = [
    ['id' => 'P-1002', 'date' => '02 Okt 2026', 'name' => 'Siti Nurhaliza', 'room' => '01', 'amount' => 500000, 'bill' => 750000, 'period' => 'Oktober 2026', 'pay' => 'dp', 'status' => 'menunggu', 'method' => 'Transfer Bank BCA', 'time' => '02 Okt 2026 · 14:15', 'proof' => 'bukti-transfer-bca.jpg', 'ref' => '012345678909'],
    ['id' => 'P-1001', 'date' => '01 Okt 2026', 'name' => 'Rina Fitria', 'room' => '03', 'amount' => 800000, 'period' => 'Oktober 2026', 'pay' => 'full', 'status' => 'terverifikasi', 'method' => 'Transfer Bank BCA', 'time' => '01 Okt 2026 · 08:52', 'proof' => '', 'ref' => '012345671234'],
    ['id' => 'P-0930', 'date' => '30 Sep 2026', 'name' => 'Dinda Aulia', 'room' => '04', 'amount' => 950000, 'period' => 'Oktober 2026', 'pay' => 'full', 'status' => 'terverifikasi', 'method' => 'Transfer Bank BCA', 'time' => '30 Sep 2026 · 19:40', 'proof' => '', 'ref' => '012345665432'],
];
foreach ($pembayaran as $i => $p) {
    if (isset($ubah_status[$p['id']])) {
        $pembayaran[$i]['status'] = $ubah_status[$p['id']];
    }
}

$status_badge = [
    'menunggu' => ['Menunggu', 'warning'],
    'terverifikasi' => ['Terverifikasi', 'success'],
    'ditolak' => ['Ditolak', 'danger'],
];

$app_data = ['payments' => $pembayaran];
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Verifikasi Pembayaran</h1>
        <p class="page-subtitle">Periksa dan verifikasi bukti transfer pembayaran dari penghuni</p>
    </div>
</div>
<div data-banner-slot>
    <?php if ($banner_sukses != '') { ?>
    <div class="banner banner-success" role="status">
        <div class="banner-body"><i class="icon icon-circle-check" aria-hidden="true"></i><span><?php echo $banner_sukses; ?></span></div>
    </div>
    <?php } ?>
</div>
<div class="list-section" data-list>
    <div class="toolbar">
        <div class="chips" role="group" aria-label="Filter status"><button type="button" class="chip is-active" aria-pressed="true" data-filter="all" data-label="Semua" data-count>Semua</button><button type="button" class="chip" aria-pressed="false" data-filter="menunggu" data-label="Menunggu" data-count>Menunggu</button><button type="button" class="chip" aria-pressed="false" data-filter="terverifikasi" data-label="Terverifikasi" data-count>Terverifikasi</button><button type="button" class="chip" aria-pressed="false" data-filter="ditolak" data-label="Ditolak" data-count data-hide-empty>Ditolak</button></div>
        <label class="search search-sm"><i class="icon icon-search" aria-hidden="true"></i><span class="sr-only">Cari nama penghuni...</span><input type="search" placeholder="Cari nama penghuni..." value="" data-search autocomplete="off"><button type="button" class="search-clear" aria-label="Hapus pencarian"><i class="icon icon-x" aria-hidden="true"></i></button></label>
    </div>
    <div class="card">
        <div class="table-wrap">
            <table class="table table-verifikasi">
                <thead>
                <tr>
                    <th>Tanggal Kirim</th>
                    <th>Penghuni</th>
                    <th>Status</th>
                    <th class="ta-right">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pembayaran as $p) { ?>
                <tr data-item data-group="pembayaran" data-id="<?php echo $p['id']; ?>" data-status="<?php echo $p['status']; ?>" data-text="<?php echo strtolower($p['name']); ?> kamar <?php echo $p['room']; ?>">
                    <td class="nowrap"><?php echo $p['date']; ?></td>
                    <td>
                        <div class="person">
                            <span class="avatar"><?php echo inisial($p['name']); ?></span>
                            <div><p class="person-name"><?php echo $p['name']; ?></p><p class="person-sub">Kamar <?php echo $p['room']; ?></p></div>
                        </div>
                    </td>
                    <td data-badge><span class="badge badge-<?php echo $status_badge[$p['status']][1]; ?>"><span class="badge-dot"></span><span class="badge-label"><?php echo $status_badge[$p['status']][0]; ?></span></span></td>
                    <td>
                        <div class="row-actions" data-when="menunggu">
                            <button type="button" class="btn btn-primary btn-sm" data-proof="<?php echo $p['id']; ?>">Verifikasi</button>
                        </div>
                        <div class="row-actions" data-when="terverifikasi ditolak">
                            <button type="button" class="btn btn-secondary btn-sm" data-proof="<?php echo $p['id']; ?>">Detail</button>
                        </div>
                    </td>
                </tr>
                <?php } ?>
                <tr class="table-empty" data-empty hidden><td colspan="4"><i class="icon icon-search" aria-hidden="true"></i>Tidak ada pembayaran yang cocok.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal" id="modal-bukti" role="dialog" aria-modal="true" aria-labelledby="bukti-title" data-open-id="<?php echo $buka_bukti; ?>" hidden>
    <div class="modal-dialog modal-lg">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="bukti-title">Detail Bukti Transfer</h2>
                <p class="modal-subtitle" data-f="subtitle"></p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <div class="proof-layout">
            <div class="media media-zoom" data-f="media" data-zoom-alt="Bukti transfer"></div>
            <div>
                <h3 class="detail-title">Ringkasan Tagihan</h3>
                <div class="detail-list">
                    <div class="detail-row"><span class="detail-label">Periode</span><span class="detail-value" data-f="period"></span></div>
                    <div class="detail-row"><span class="detail-label">Tagihan Sewa</span><span class="detail-value" data-f="bill"></span></div>
                    <div class="detail-row"><span class="detail-label">Nominal Ditransfer</span><span class="detail-value" data-f="paid"></span></div>
                    <div class="detail-row"><span class="detail-label">Tipe Bayar</span><span class="detail-value" data-f="paytype"></span></div>
                    <div class="detail-row"><span class="detail-label">Metode</span><span class="detail-value" data-f="method"></span></div>
                    <div class="detail-row"><span class="detail-label">Tanggal Transfer</span><span class="detail-value" data-f="time"></span></div>
                    <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value" data-f="status"></span></div>
                    <div class="detail-row" data-f="rest-row" hidden><span class="detail-label">Sisa Bayar</span><span class="detail-value detail-value-danger" data-f="rest"></span></div>
                </div>
                <div class="note-box note-success" data-f="match"><i class="icon icon-circle-check" aria-hidden="true"></i><span>Nominal sesuai dengan tagihan</span></div>
                <div class="reason-box" data-f="reason-box" hidden>
                    <p class="reason-title"><i class="icon icon-triangle-alert" aria-hidden="true"></i>Alasan Penolakan</p>
                    <p class="reason-text" data-f="reason"></p>
                    <p class="reason-meta" data-f="reason-meta"></p>
                </div>
            </div>
        </div>
        <div class="modal-actions" data-f="actions">
            <button type="button" class="btn btn-danger-outline btn-lg" data-f="reject">Tolak</button>
            <button type="button" class="btn btn-primary btn-lg" data-f="confirm">Konfirmasi Pembayaran</button>
        </div>
    </div>
</div>
<div class="modal" id="modal-tolak" role="dialog" aria-modal="true" aria-labelledby="tolak-title" hidden>
    <div class="modal-dialog modal-md">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="tolak-title">Alasan Penolakan Pembayaran</h2>
                <p class="modal-subtitle" data-f="subtitle"></p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <form class="form-grid" id="form-tolak" novalidate>
            <div class="field">
                <label class="field-label" for="tolak-alasan">Alasan Penolakan <span class="field-required">*</span></label>
                <textarea class="textarea" id="tolak-alasan" rows="4" maxlength="300" placeholder="Ketik alasan pembayaran ditolak. Alasan ini akan dilihat oleh penghuni." aria-describedby="tolak-error"></textarea>
                <span class="field-error" id="tolak-error" hidden>Alasan penolakan wajib diisi sebelum menolak pembayaran.</span>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary btn-lg" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-danger btn-lg">Konfirmasi Penolakan</button>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
