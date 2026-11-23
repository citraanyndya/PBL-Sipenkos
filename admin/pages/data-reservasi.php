<?php
$page_title = 'Data Reservasi';
$page_id = 'data-reservasi';
$page_module = 'reservasi';
$activeMenu = 'reservasi';
include __DIR__ . '/../includes/header.php';

$reservasi = [
    ['id' => 'R-1015', 'date' => '15 Okt 2026', 'name' => 'Nadia Putri', 'phone' => '0812–3456–7781', 'type' => 'Tipe 1 Non-AC, Kamar Mandi Luar', 'duration' => '6 Bulan', 'pay' => 'dp', 'status' => 'menunggu', 'start' => '01 Nov 2026', 'email' => 'nadia.putri@gmail.com', 'amount' => 1500000,
     'ktp' => ['nik' => '3171024508010003', 'birth' => 'Bandung, 05-08-2001', 'gender' => 'PEREMPUAN', 'address' => 'JL. MELATI NO. 21', 'job' => 'MAHASISWA']],
    ['id' => 'R-0928', 'date' => '28 Sep 2026', 'name' => 'Dinda Aulia', 'phone' => '0857–1122–3344', 'type' => 'Tipe 4 AC, Kamar Mandi Dalam', 'duration' => '12 Bulan', 'pay' => 'full', 'status' => 'diterima', 'start' => '01 Okt 2026', 'email' => 'dinda.aulia@gmail.com', 'amount' => 11400000,
     'ktp' => ['nik' => '3171016203020005', 'birth' => 'Jakarta, 22-03-2002', 'gender' => 'PEREMPUAN', 'address' => 'JL. KENANGA NO. 8', 'job' => 'KARYAWAN SWASTA']],
    ['id' => 'R-0920', 'date' => '20 Sep 2026', 'name' => 'Rina Fitria', 'phone' => '0813–9988–7766', 'type' => 'Tipe 3 AC, Kamar Mandi Luar', 'duration' => '6 Bulan', 'pay' => 'full', 'status' => 'diterima', 'start' => '01 Okt 2026', 'email' => 'rina.fitria@gmail.com', 'amount' => 4800000,
     'ktp' => ['nik' => '3171044111000002', 'birth' => 'Bogor, 01-11-2000', 'gender' => 'PEREMPUAN', 'address' => 'JL. ANGGREK NO. 14', 'job' => 'KARYAWAN SWASTA']],
    ['id' => 'R-0912', 'date' => '12 Sep 2026', 'name' => 'Bima Pratama', 'phone' => '0821–5566–4433', 'type' => 'Tipe 3 AC, Kamar Mandi Luar', 'duration' => '1 Bulan', 'pay' => 'full', 'status' => 'ditolak', 'reason' => 'Kamar tipe 3 sudah penuh untuk tanggal mulai yang diajukan.', 'start' => '15 Sep 2026', 'email' => 'bima.pratama@gmail.com', 'amount' => 800000,
     'ktp' => ['nik' => '3171031709990001', 'birth' => 'Depok, 17-09-1999', 'gender' => 'LAKI-LAKI', 'address' => 'JL. CEMPAKA NO. 3', 'job' => 'WIRASWASTA']],
];

$status_badge = [
    'diterima' => ['Diterima', 'success'],
    'ditolak' => ['Ditolak', 'danger'],
];

$app_data = ['reservations' => $reservasi];
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Data Reservasi</h1>
        <p class="page-subtitle">Daftar pengajuan pemesanan kamar dari calon penghuni</p>
    </div>
</div>
<div class="list-section" data-list>
    <div class="toolbar toolbar-end">
        <label class="search"><i class="icon icon-search" aria-hidden="true"></i><span class="sr-only">Cari nama calon penghuni...</span><input type="search" placeholder="Cari nama calon penghuni..." value="" data-search autocomplete="off"><button type="button" class="search-clear" aria-label="Hapus pencarian"><i class="icon icon-x" aria-hidden="true"></i></button></label>
    </div>
    <div class="card">
        <div class="table-wrap">
            <table class="table table-reservasi">
                <thead>
                <tr>
                    <th class="th-wrap">Tanggal<br>Pengajuan</th>
                    <th>Calon Penghuni</th>
                    <th>Tipe Kamar</th>
                    <th>Durasi</th>
                    <th>Bukti KTP</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($reservasi as $r) { ?>
                <tr data-item data-group="reservasi" data-id="<?php echo $r['id']; ?>" data-status="<?php echo $r['status']; ?>" data-text="<?php echo strtolower($r['name'] . ' ' . $r['phone'] . ' ' . $r['type']); ?>">
                    <td class="nowrap"><?php echo $r['date']; ?></td>
                    <td>
                        <div class="person">
                            <span class="avatar"><?php echo inisial($r['name']); ?></span>
                            <div><p class="person-name"><?php echo $r['name']; ?></p><p class="person-sub"><?php echo $r['phone']; ?></p></div>
                        </div>
                    </td>
                    <td class="cell-wrap col-type"><?php echo $r['type']; ?></td>
                    <td class="nowrap"><?php echo $r['duration']; ?></td>
                    <td><button type="button" class="link link-icon" data-res-ktp="<?php echo $r['id']; ?>"><i class="icon icon-id-card" aria-hidden="true"></i>Lihat KTP</button></td>
                    <?php if ($r['status'] == 'menunggu') { ?>
                        <td data-badge><div class="status-actions" data-decision><button type="button" class="btn btn-primary btn-sm" data-res-action="terima">Terima</button><button type="button" class="btn btn-danger-outline btn-sm" data-res-action="tolak">Tolak</button></div></td>
                    <?php } else { ?>
                        <td data-badge><span class="badge badge-<?php echo $status_badge[$r['status']][1]; ?>"><span class="badge-dot"></span><span class="badge-label"><?php echo $status_badge[$r['status']][0]; ?></span></span></td>
                    <?php } ?>
                </tr>
                <?php } ?>
                <tr class="table-empty" data-empty hidden><td colspan="6"><i class="icon icon-search" aria-hidden="true"></i>Tidak ada reservasi yang cocok.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal" id="modal-ktp" role="dialog" aria-modal="true" aria-labelledby="ktp-title" hidden>
    <div class="modal-dialog">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="ktp-title">Bukti KTP Calon Penghuni</h2>
                <p class="modal-subtitle" data-f="subtitle"></p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <div class="doc-layout">
            <div class="media" data-f="media"></div>
            <div class="detail-list">
                <div class="detail-row"><span class="detail-label">Nama Lengkap</span><span class="detail-value" data-f="name"></span></div>
                <div class="detail-row"><span class="detail-label">NIK</span><span class="detail-value" data-f="nik"></span></div>
                <div class="detail-row"><span class="detail-label">No. HP</span><span class="detail-value" data-f="phone"></span></div>
                <div class="detail-row"><span class="detail-label">Tipe Kamar</span><span class="detail-value" data-f="type"></span></div>
                <div class="detail-row"><span class="detail-label">Status Reservasi</span><span class="detail-value" data-f="status"></span></div>
            </div>
        </div>
        <div class="reason-box" data-f="reason-box" hidden>
            <p class="reason-title"><i class="icon icon-triangle-alert" aria-hidden="true"></i>Alasan Penolakan</p>
            <p class="reason-text" data-f="reason"></p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary btn-lg" data-modal-close>Tutup</button>
        </div>
    </div>
</div>
<div class="modal" id="modal-tolak-res" role="dialog" aria-modal="true" aria-labelledby="tolak-res-title" hidden>
    <div class="modal-dialog modal-md">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="tolak-res-title">Alasan Penolakan Reservasi</h2>
                <p class="modal-subtitle" data-f="subtitle"></p>
            </div>
            <button type="button" class="modal-close" data-modal-close aria-label="Tutup"><i class="icon icon-x" aria-hidden="true"></i></button>
        </div>
        <form class="form-grid" id="form-tolak-res" novalidate>
            <div class="field">
                <label class="field-label" for="tolak-res-alasan">Alasan Penolakan <span class="field-required">*</span></label>
                <textarea class="textarea" id="tolak-res-alasan" rows="4" maxlength="300" placeholder="Ketik alasan reservasi ditolak. Alasan ini akan dilihat oleh calon penghuni." aria-describedby="tolak-res-error"></textarea>
                <span class="field-error" id="tolak-res-error" hidden>Alasan penolakan wajib diisi sebelum menolak reservasi.</span>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary btn-lg" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-danger btn-lg">Konfirmasi Penolakan</button>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
