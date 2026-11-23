<?php
$page_title = $page_title ?? 'Monitor Kamar';
$page_id = $page_id ?? 'monitor-kamar';
$page_module = 'kamar';
$activeMenu = 'kamar';
include __DIR__ . '/../includes/header.php';

$mode_kelola = $mode_kelola ?? false;

$tipe_kamar = [
    ['id' => 1, 'name' => 'Tipe 1 Non-AC, Kamar Mandi Luar', 'short' => 'Tipe 1 Non-AC, KM Luar', 'price' => 500000, 'total' => 7, 'filled' => 6],
    ['id' => 2, 'name' => 'Tipe 2 Non-AC, Kamar Mandi Dalam', 'short' => 'Tipe 2 Non-AC, KM Dalam', 'price' => 650000, 'total' => 5, 'filled' => 3],
    ['id' => 3, 'name' => 'Tipe 3 AC, Kamar Mandi Luar', 'short' => 'Tipe 3 AC, KM Luar', 'price' => 800000, 'total' => 3, 'filled' => 3],
    ['id' => 4, 'name' => 'Tipe 4 AC, Kamar Mandi Dalam', 'short' => 'Tipe 4 AC, KM Dalam', 'price' => 950000, 'total' => 5, 'filled' => 2],
];

$totalKamar = 0;
$kamarTerisi = 0;
foreach ($tipe_kamar as $t) {
    $totalKamar += $t['total'];
    $kamarTerisi += $t['filled'];
}
$kamarKosong = $totalKamar - $kamarTerisi;

$app_data = ['roomTypes' => $tipe_kamar];
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Monitor Kamar</h1>
        <p class="page-subtitle">Pantau ketersediaan dan tingkat okupansi kamar kos</p>
    </div>
</div>
<section class="stats stats-3" aria-label="Ringkasan kamar">
    <div class="card stat stat-row">
        <span class="icon-box icon-box-lg tone-blue"><i class="icon icon-bed-double" aria-hidden="true"></i></span>
        <div><p class="stat-label">Total Kamar</p><p class="stat-value" data-stat="total"><?php echo $totalKamar; ?></p></div>
    </div>
    <div class="card stat stat-row">
        <span class="icon-box icon-box-lg tone-green"><i class="icon icon-users" aria-hidden="true"></i></span>
        <div><p class="stat-label">Kamar Terisi</p><p class="stat-value" data-stat="filled"><?php echo $kamarTerisi; ?></p></div>
    </div>
    <div class="card stat stat-row">
        <span class="icon-box icon-box-lg tone-amber"><i class="icon icon-door-open" aria-hidden="true"></i></span>
        <div><p class="stat-label">Kamar Kosong</p><p class="stat-value" data-stat="empty"><?php echo $kamarKosong; ?></p></div>
    </div>
</section>
<div class="card">
    <div class="card-head card-head-bar">
        <h2 class="card-title">Ketersediaan per Tipe Kamar</h2>
        <button type="button" class="btn btn-primary btn-sm" data-kelola="3">Kelola Ketersediaan</button>
    </div>
    <div class="table-wrap">
        <table class="table table-kamar<?php if ($mode_kelola) { echo ' has-actions'; } ?>">
            <thead>
            <tr>
                <th>Tipe Kamar</th>
                <th>Harga / Bulan</th>
                <th>Total</th>
                <th>Terisi</th>
                <th>Kosong</th>
                <?php if ($mode_kelola) { ?>
                    <th>Status</th>
                    <th class="ta-right">Aksi</th>
                <?php } else { ?>
                    <th class="ta-center">Status</th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tipe_kamar as $i => $t) {
                $kosong = $t['total'] - $t['filled']; ?>
                <tr data-room-row="<?php echo $i; ?>">
                    <td class="<?php echo $mode_kelola ? 'cell-wrap col-type-wide' : 'nowrap'; ?>"><?php echo $t['name']; ?></td>
                    <td class="nowrap"><?php echo rupiah($t['price']); ?></td>
                    <td data-c="total"><?php echo $t['total']; ?></td>
                    <td data-c="filled"><?php echo $t['filled']; ?></td>
                    <td data-c="empty"><?php echo $kosong; ?></td>
                    <td data-c="status"<?php if (!$mode_kelola) { echo ' class="ta-center"'; } ?>>
                        <?php if ($kosong > 0) { ?>
                            <span class="badge badge-success"><span class="badge-dot"></span><span class="badge-label">Tersedia · sisa <?php echo $kosong; ?></span></span>
                        <?php } else { ?>
                            <span class="badge badge-danger"><span class="badge-dot"></span><span class="badge-label">Penuh</span></span>
                        <?php } ?>
                    </td>
                    <?php if ($mode_kelola) { ?>
                        <td class="ta-right"><button type="button" class="btn btn-secondary btn-sm" data-kelola="<?php echo $i; ?>">Kelola</button></td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal" id="modal-kamar" role="dialog" aria-modal="true" aria-labelledby="kamar-title" data-initial="3"<?php if ($mode_kelola) { echo ' data-autoopen'; } ?> hidden>
    <div class="modal-dialog modal-md">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="kamar-title">Kelola Ketersediaan Kamar</h2>
                <p class="modal-subtitle">Perubahan langsung tampil di halaman Daftar Kamar</p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <div class="form-grid">
            <div class="field">
                <label class="field-label" for="kelola-tipe">Tipe Kamar</label>
                <select class="select" id="kelola-tipe">
                    <?php foreach ($tipe_kamar as $i => $t) { ?>
                        <option value="<?php echo $i; ?>"<?php if ($i == 3) { echo ' selected'; } ?>><?php echo $t['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-grid form-grid-2">
                <div class="field">
                    <label class="field-label" for="kelola-total">Total Kamar</label>
                    <div class="stepper">
                        <button type="button" data-step="-1" data-target="kelola-total" aria-label="Kurangi total kamar"><i class="icon icon-minus" aria-hidden="true"></i></button>
                        <input type="number" id="kelola-total" min="1" max="99" value="5" inputmode="numeric">
                        <button type="button" data-step="1" data-target="kelola-total" aria-label="Tambah total kamar"><i class="icon icon-plus" aria-hidden="true"></i></button>
                    </div>
                </div>
                <div class="field">
                    <label class="field-label" for="kelola-terisi">Kamar Terisi</label>
                    <div class="stepper">
                        <button type="button" data-step="-1" data-target="kelola-terisi" aria-label="Kurangi kamar terisi"><i class="icon icon-minus" aria-hidden="true"></i></button>
                        <input type="number" id="kelola-terisi" min="0" max="99" value="2" inputmode="numeric">
                        <button type="button" data-step="1" data-target="kelola-terisi" aria-label="Tambah kamar terisi"><i class="icon icon-plus" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
            <div class="note-box note-info" data-f="info"><i class="icon icon-info" aria-hidden="true"></i><span>Kamar kosong otomatis dihitung: 3 kamar · status Tersedia</span></div>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary btn-lg" data-modal-close>Batal</button>
            <button type="button" class="btn btn-primary btn-lg" data-f="save">Simpan Perubahan</button>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
