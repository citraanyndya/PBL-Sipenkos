<?php
$page_title = 'Laporan Keuangan';
$page_id = 'laporan-keuangan';
$page_module = 'keuangan';
$activeMenu = 'keuangan';
include __DIR__ . '/../includes/header.php';

$keuangan = [
    '2026-10' => [
        'label' => 'Oktober 2026', 'gross' => 10050000, 'filled' => 14,
        'expenses' => [
            ['date' => '2026-10-01', 'category' => 'rutin', 'desc' => 'Biaya listrik induk kos', 'amount' => 1200000, 'created' => '2026-10-01T08:05:00'],
            ['date' => '2026-10-01', 'category' => 'rutin', 'desc' => 'Biaya Wi-Fi induk kos', 'amount' => 500000, 'created' => '2026-10-01T08:20:00'],
            ['date' => '2026-10-01', 'category' => 'rutin', 'desc' => 'Biaya kebersihan dan penjaga kos', 'amount' => 1000000, 'created' => '2026-10-01T08:40:00'],
            ['date' => '2026-10-05', 'category' => 'perbaikan', 'desc' => 'Servis AC Kamar 01 (laporan Siti Nurhaliza)', 'amount' => 300000, 'created' => '2026-10-05T14:10:00'],
        ],
    ],
    '2026-09' => [
        'label' => 'September 2026', 'gross' => 9200000, 'filled' => 13,
        'expenses' => [
            ['date' => '2026-09-01', 'category' => 'rutin', 'desc' => 'Biaya listrik induk kos', 'amount' => 1150000, 'created' => '2026-09-01T08:05:00'],
            ['date' => '2026-09-01', 'category' => 'rutin', 'desc' => 'Biaya Wi-Fi induk kos', 'amount' => 500000, 'created' => '2026-09-01T08:20:00'],
            ['date' => '2026-09-01', 'category' => 'rutin', 'desc' => 'Biaya kebersihan dan penjaga kos', 'amount' => 1000000, 'created' => '2026-09-01T08:40:00'],
            ['date' => '2026-09-22', 'category' => 'perbaikan', 'desc' => 'Perbaikan keran wastafel Kamar 03', 'amount' => 150000, 'created' => '2026-09-22T15:25:00'],
        ],
    ],
];

$kategori = [
    'rutin' => ['Operasional Rutin', 'neutral'],
    'perbaikan' => ['Perbaikan Fasilitas', 'amber'],
];

$bulan = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'];

$periode_aktif = '2026-10';
$data = $keuangan[$periode_aktif];

$totalPengeluaran = 0;
foreach ($data['expenses'] as $x) {
    $totalPengeluaran += $x['amount'];
}
$pendapatanBersih = $data['gross'] - $totalPengeluaran;

$app_data = ['finance' => ['periods' => $keuangan]];
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Keuangan</h1>
        <p class="page-subtitle">Pantau pendapatan, pengeluaran operasional, dan pendapatan bersih kos</p>
    </div>
    <div class="page-actions">
        <div class="dropdown dropdown-select" data-dropdown data-period-select data-current="<?php echo $periode_aktif; ?>">
            <button type="button" class="select-btn" data-dropdown-toggle aria-haspopup="listbox" aria-expanded="false"><i class="icon icon-calendar" aria-hidden="true"></i><span data-dropdown-label><?php echo $data['label']; ?></span><i class="icon icon-chevron-down" aria-hidden="true"></i></button>
            <div class="dropdown-menu" role="listbox">
                <?php foreach ($keuangan as $kode => $k) { ?>
                    <button type="button" class="dropdown-item<?php if ($kode == $periode_aktif) { echo ' is-selected'; } ?>" role="option" data-value="<?php echo $kode; ?>"><?php echo $k['label']; ?></button>
                <?php } ?>
            </div>
        </div>
        <button type="button" class="btn btn-secondary btn-sm" data-download-report>Unduh Laporan</button>
    </div>
</div>
<section class="stats stats-3" aria-label="Ringkasan keuangan">
    <div class="card stat stat-money">
        <div class="stat-top"><span class="icon-box tone-green"><i class="icon icon-trending-up" aria-hidden="true"></i></span><p class="stat-label">Pendapatan Kotor</p></div>
        <div><p class="stat-value" data-fin="gross"><?php echo rupiah($data['gross']); ?></p><p class="stat-note" data-fin="gross-note">Dari <?php echo $data['filled']; ?> kamar terisi</p></div>
    </div>
    <div class="card stat stat-money">
        <div class="stat-top"><span class="icon-box tone-red"><i class="icon icon-trending-down" aria-hidden="true"></i></span><p class="stat-label">Total Pengeluaran</p></div>
        <div><p class="stat-value" data-fin="expense"><?php echo rupiah($totalPengeluaran); ?></p><p class="stat-note" data-fin="expense-note"><?php echo count($data['expenses']); ?> transaksi operasional</p></div>
    </div>
    <div class="card stat stat-money">
        <div class="stat-top"><span class="icon-box tone-blue"><i class="icon icon-wallet" aria-hidden="true"></i></span><p class="stat-label">Pendapatan Bersih</p></div>
        <div><p class="stat-value stat-value-primary" data-fin="net"><?php echo rupiah($pendapatanBersih); ?></p><p class="stat-note">Pendapatan kotor − pengeluaran</p></div>
    </div>
</section>
<div class="card">
    <div class="card-head card-head-bar">
        <h2 class="card-title">Pengeluaran Operasional</h2>
        <button type="button" class="btn btn-primary btn-sm" data-new-expense>+ Tambah Pengeluaran</button>
    </div>
    <div class="table-wrap">
        <table class="table table-keuangan">
            <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th class="ta-right">Nominal</th>
            </tr>
            </thead>
            <tbody id="expense-body">
            <?php foreach ($data['expenses'] as $x) {
                $tgl = explode('-', $x['date']);
                $k = $kategori[$x['category']]; ?>
                <tr>
                    <td class="nowrap"><?php echo $tgl[2] . ' ' . $bulan[$tgl[1]] . ' ' . $tgl[0]; ?></td>
                    <td><span class="badge badge-<?php echo $k[1]; ?>"><span class="badge-dot"></span><span class="badge-label"><?php echo $k[0]; ?></span></span></td>
                    <td><?php echo $x['desc']; ?></td>
                    <td class="ta-right nowrap"><?php echo rupiah($x['amount']); ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <td>Total</td>
                <td></td>
                <td></td>
                <td class="ta-right nowrap" id="expense-total"><?php echo rupiah($totalPengeluaran); ?></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="modal" id="modal-pengeluaran" role="dialog" aria-modal="true" aria-labelledby="exp-title" hidden>
    <div class="modal-dialog modal-form">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="exp-title">Tambah Pengeluaran Operasional</h2>
                <p class="modal-subtitle modal-subtitle-lg">Masukkan rincian pengeluaran kos baru</p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <form id="form-pengeluaran" class="form-grid" novalidate>
            <div class="form-grid form-grid-2">
                <div class="field">
                    <label class="field-label" for="exp-date">Tanggal Transaksi</label>
                    <input class="input" type="date" id="exp-date" value="2026-10-01" min="2026-09-01" max="2026-10-31" data-required>
                    <span class="field-error" hidden>Tanggal wajib diisi</span>
                </div>
                <div class="field">
                    <label class="field-label" for="exp-category">Kategori Pengeluaran</label>
                    <select class="select" id="exp-category" data-required>
                        <?php foreach ($kategori as $kode => $k) { ?>
                            <option value="<?php echo $kode; ?>"<?php if ($kode == 'rutin') { echo ' selected'; } ?>><?php echo $k[0]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="field">
                <label class="field-label" for="exp-desc">Keterangan Pengeluaran</label>
                <input class="input" type="text" id="exp-desc" placeholder="Contoh: Biaya air kos" value="" maxlength="80" data-required>
                <span class="field-error" hidden>Keterangan wajib diisi</span>
            </div>
            <div class="field">
                <label class="field-label" for="exp-amount">Nominal (Rp)</label>
                <div class="input-prefix"><span>Rp.</span><input class="input" type="text" id="exp-amount" inputmode="numeric" placeholder="0" value="" data-required></div>
                <span class="field-error" hidden>Nominal wajib diisi</span>
            </div>
            <div class="modal-actions modal-actions-form">
                <button type="button" class="btn btn-text btn-lg" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary btn-lg">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
