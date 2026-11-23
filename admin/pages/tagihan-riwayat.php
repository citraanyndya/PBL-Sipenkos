<?php
$page_title = $page_title ?? 'Tagihan &amp; Riwayat';
$page_id = $page_id ?? 'tagihan-riwayat';
$page_module = 'tagihan';
$activeMenu = 'tagihan';
include __DIR__ . '/../includes/header.php';

$periode_aktif = $periode_aktif ?? '2026-10';
$cari = $cari ?? '';
$pesan_pengingat = $pesan_pengingat ?? '';

$periode = [
    '2026-10' => 'Oktober 2026',
    '2026-09' => 'September 2026',
    '2026-08' => 'Agustus 2026',
    '2026-07' => 'Juli 2026',
    'all' => 'Semua Periode',
];

$tagihan = [
    ['name' => 'Nana', 'room' => '07', 'type' => 'Tipe 4 AC, Kamar Mandi Dalam', 'amount' => 950000, 'period' => '2026-10', 'due' => '27 Okt 2026', 'paid' => '', 'status' => 'belum', 'kind' => 'sewa'],
    ['name' => 'Anin', 'room' => '02', 'type' => 'Tipe 1 Non-AC, Kamar Mandi Luar', 'amount' => 250000, 'period' => '2026-10', 'due' => '27 Okt 2026', 'paid' => '', 'status' => 'belum', 'kind' => 'sisa-dp'],
    ['name' => 'Rina Fitria', 'room' => '03', 'type' => 'Tipe 3 AC, Kamar Mandi Luar', 'amount' => 800000, 'period' => '2026-10', 'due' => '27 Okt 2026', 'paid' => '01 Okt 2026', 'status' => 'lunas', 'kind' => 'sewa', 'time' => '01 Okt 2026 · 08:52', 'ref' => '012345671234'],
    ['name' => 'Dinda Aulia', 'room' => '04', 'type' => 'Tipe 4 AC, Kamar Mandi Dalam', 'amount' => 950000, 'period' => '2026-10', 'due' => '27 Okt 2026', 'paid' => '30 Sep 2026', 'status' => 'lunas', 'kind' => 'sewa', 'time' => '30 Sep 2026 · 19:40', 'ref' => '012345665432'],
    ['name' => 'Nana', 'room' => '07', 'type' => 'Tipe 4 AC, Kamar Mandi Dalam', 'amount' => 950000, 'period' => '2026-09', 'due' => '27 Sep 2026', 'paid' => '25 Sep 2026', 'status' => 'lunas', 'kind' => 'sewa', 'time' => '25 Sep 2026 · 10:12', 'ref' => '012345650011'],
    ['name' => 'Siti Nurhaliza', 'room' => '01', 'type' => 'Tipe 1 Non-AC, Kamar Mandi Luar', 'amount' => 500000, 'period' => '2026-09', 'due' => '27 Sep 2026', 'paid' => '20 Sep 2026', 'status' => 'lunas', 'kind' => 'sewa', 'time' => '20 Sep 2026 · 16:05', 'ref' => '012345644221'],
    ['name' => 'Nana', 'room' => '07', 'type' => 'Tipe 4 AC, Kamar Mandi Dalam', 'amount' => 950000, 'period' => '2026-08', 'due' => '27 Agu 2026', 'paid' => '26 Agu 2026', 'status' => 'lunas', 'kind' => 'sewa', 'time' => '26 Agu 2026 · 09:30', 'ref' => '012345630087'],
    ['name' => 'Nana', 'room' => '07', 'type' => 'Tipe 4 AC, Kamar Mandi Dalam', 'amount' => 950000, 'period' => '2026-07', 'due' => '27 Jul 2026', 'paid' => '27 Jul 2026', 'status' => 'lunas', 'kind' => 'sewa', 'time' => '27 Jul 2026 · 11:45', 'ref' => '012345610342'],
];

$status_badge = [
    'belum' => ['Belum Bayar', 'danger'],
    'lunas' => ['Sudah Lunas', 'success'],
];
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Tagihan &amp; Riwayat Pembayaran</h1>
        <p class="page-subtitle">Pantau status tagihan bulanan dan riwayat transaksi penghuni</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-secondary btn-sm" data-massal>Kirim Pengingat Massal</button>
    </div>
</div>
<div class="list-section" data-list data-hide-empty-chips>
    <div class="toolbar">
        <div class="chips" role="group" aria-label="Filter status"><button type="button" class="chip is-active" aria-pressed="true" data-filter="all" data-label="Semua" data-count>Semua</button><button type="button" class="chip" aria-pressed="false" data-filter="belum" data-label="Belum Bayar" data-count>Belum Bayar</button><button type="button" class="chip" aria-pressed="false" data-filter="lunas" data-label="Sudah Lunas" data-count>Sudah Lunas</button></div>
        <div class="toolbar-right">
            <div class="dropdown dropdown-select" data-dropdown data-period-select data-current="<?php echo $periode_aktif; ?>">
                <button type="button" class="select-btn" data-dropdown-toggle aria-haspopup="listbox" aria-expanded="false"><i class="icon icon-calendar" aria-hidden="true"></i><span data-dropdown-label><?php echo $periode[$periode_aktif]; ?></span><i class="icon icon-chevron-down" aria-hidden="true"></i></button>
                <div class="dropdown-menu" role="listbox">
                    <?php foreach ($periode as $kode => $nama) { ?>
                        <button type="button" class="dropdown-item<?php if ($kode == $periode_aktif) { echo ' is-selected'; } ?>" role="option" data-value="<?php echo $kode; ?>"><?php echo $nama; ?></button>
                    <?php } ?>
                </div>
            </div>
            <label class="search search-md"><i class="icon icon-search" aria-hidden="true"></i><span class="sr-only">Cari nama penghuni...</span><input type="search" placeholder="Cari nama penghuni..." value="<?php echo $cari; ?>" data-search autocomplete="off"><button type="button" class="search-clear" aria-label="Hapus pencarian"><i class="icon icon-x" aria-hidden="true"></i></button></label>
        </div>
    </div>
    <div data-banner-slot></div>
    <div class="card">
        <div class="table-wrap">
            <table class="table table-tagihan">
                <thead>
                <tr>
                    <th>Penghuni</th>
                    <th>Tagihan</th>
                    <th>Jatuh Tempo</th>
                    <th>Tanggal Bayar</th>
                    <th>Status</th>
                    <th class="ta-right">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tagihan as $t) {
                    $teks_cari = strtolower($t['name']) . ' kamar ' . $t['room'];
                    if ($t['kind'] == 'sisa-dp') { $teks_cari .= ' sisa dp down payment'; } ?>
                    <tr data-item data-group="tagihan" data-status="<?php echo $t['status']; ?>" data-period="<?php echo $t['period']; ?>"
                        data-text="<?php echo $teks_cari; ?>"
                        data-name="<?php echo $t['name']; ?>" data-room="<?php echo $t['room']; ?>" data-type="<?php echo $t['type']; ?>"
                        data-amount="<?php echo rupiah($t['amount']); ?>" data-period-label="<?php echo $periode[$t['period']]; ?>"
                        data-due="<?php echo $t['due']; ?>" data-paid="<?php echo $t['paid']; ?>" data-amount-value="<?php echo $t['amount']; ?>"
                        <?php if ($t['status'] == 'lunas') { ?>data-pay="full" data-method="Transfer Bank BCA" data-time="<?php echo $t['time']; ?>" data-ref="<?php echo $t['ref']; ?>"<?php } ?>
                        <?php if ($t['kind'] == 'sisa-dp') { ?>data-bill-kind="sisa-dp"<?php } ?>>
                        <td>
                            <div class="person">
                                <span class="avatar"><?php echo inisial($t['name']); ?></span>
                                <div>
                                    <button type="button" class="person-name person-link" data-person-filter="<?php echo $t['name']; ?>" title="Lihat riwayat <?php echo $t['name']; ?>"><?php echo $t['name']; ?></button>
                                    <p class="person-sub">Kamar <?php echo $t['room']; ?></p>
                                </div>
                            </div>
                        </td>
                        <td><p class="cell-main"><?php echo rupiah($t['amount']); ?></p><p class="cell-sub"><?php if ($t['kind'] == 'sisa-dp') { echo 'Sisa DP · '; } ?><?php echo $periode[$t['period']]; ?></p></td>
                        <td class="nowrap"><?php echo $t['due']; ?></td>
                        <td class="nowrap"><?php if ($t['paid'] != '') { echo $t['paid']; } else { echo '<span class="text-muted">-</span>'; } ?></td>
                        <td data-badge><span class="badge badge-<?php echo $status_badge[$t['status']][1]; ?>"><span class="badge-dot"></span><span class="badge-label"><?php echo $status_badge[$t['status']][0]; ?></span></span></td>
                        <td>
                            <div class="row-actions" data-when="belum"><button type="button" class="btn btn-primary btn-sm" data-tagih>Tagih</button></div>
                            <div class="row-actions" data-when="lunas"><button type="button" class="btn btn-secondary btn-sm" data-bill-detail>Detail</button></div>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="table-empty" data-empty hidden><td colspan="6"><i class="icon icon-search" aria-hidden="true"></i>Tidak ada tagihan yang cocok dengan filter.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php if ($pesan_pengingat != '') { ?>
<div class="modal" id="modal-pengingat" role="dialog" aria-modal="true" aria-labelledby="pengingat-title" data-autoopen hidden>
    <div class="modal-dialog modal-sm modal-center">
        <div class="result-icon"><i class="icon icon-check" aria-hidden="true"></i></div>
        <h2 class="result-title" id="pengingat-title">Pengingat Terkirim!</h2>
        <p class="result-text"><?php echo $pesan_pengingat; ?></p>
        <div class="modal-actions"><button type="button" class="btn btn-primary btn-lg btn-block" data-modal-close>Oke</button></div>
    </div>
</div>
<?php } ?>
<div class="modal" id="modal-bukti" role="dialog" aria-modal="true" aria-labelledby="bukti-title" data-open-id="" hidden>
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
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
