<?php

function rupiah($angka): string
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

function tgl_indo($tanggal, bool $denganJam = false): string
{
    if (!$tanggal) {
        return '-';
    }
    $bulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $t = strtotime($tanggal);
    $out = date('d', $t) . ' ' . $bulan[(int) date('n', $t)] . ' ' . date('Y', $t);
    return $denganJam ? $out . ', ' . date('H:i', $t) : $out;
}

function inisial($nama): string
{
    $kata = preg_split('/\s+/', trim((string) $nama));
    $hasil = mb_strtoupper(mb_substr($kata[0] ?? '?', 0, 1));
    if (count($kata) > 1) {
        $hasil .= mb_strtoupper(mb_substr($kata[count($kata) - 1], 0, 1));
    }
    return $hasil;
}

function badge(string $status): string
{
    $peta = [
        'tersedia' => ['hijau', 'Tersedia'], 'ditahan' => ['kuning', 'Ditahan'], 'terisi' => ['biru', 'Terisi'],
        'menunggu_verifikasi' => ['kuning', 'Menunggu'], 'diterima' => ['hijau', 'Diterima'],
        'ditolak' => ['merah', 'Ditolak'], 'kedaluwarsa' => ['abu', 'Kedaluwarsa'],
        'menunggu' => ['kuning', 'Menunggu'],
        'baru' => ['merah', 'Baru'], 'diproses' => ['kuning', 'Diproses'], 'selesai' => ['hijau', 'Selesai'],
        'aktif' => ['hijau', 'Aktif'], 'calon' => ['abu', 'Calon'], 'lunas' => ['hijau', 'Lunas'],
        'belum_dibayar' => ['merah', 'Belum Dibayar'], 'menunggu_konfirmasi' => ['kuning', 'Menunggu Konfirmasi'],
    ];
    [$warna, $label] = $peta[$status] ?? ['abu', $status];
    return '<span class="badge badge-' . $warna . '">' . e($label) . '</span>';
}

/** Ekspresi SQL (PostgreSQL) untuk tanggal selesai sewa dari alias tabel pemesanan. */
function sql_selesai(string $alias = 'p'): string
{
    return "({$alias}.tgl_mulai + make_interval(months => {$alias}.durasi_bulan))::date";
}

function sql_pemesanan_aktif(string $alias = 'p'): string
{
    return "({$alias}.status_pesanan = 'menunggu_verifikasi'
             OR ({$alias}.status_pesanan = 'diterima' AND " . sql_selesai($alias) . " >= CURRENT_DATE))";
}

/** URL berkas privat (KTP, bukti transfer, foto kerusakan) lewat berkas/tampil.php. */
function url_berkas(string $jenis, $nama): ?string
{
    return $nama ? base_path() . 'berkas/tampil.php?jenis=' . urlencode($jenis) . '&f=' . urlencode($nama) : null;
}

function url_foto_kamar($nama): ?string
{
    return $nama ? base_path() . 'uploads/kamar/' . rawurlencode($nama) : null;
}

function ikon(string $nama, int $ukuran = 18): string
{
    static $p = [
        'dashboard' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',
        'dokumen'   => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
        'riwayat'   => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'kalender'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        'kalender-cek' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><polyline points="9 16 11 18 15 14"/>',
        'kunci'     => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
        'lonceng'   => '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>',
        'pengguna'  => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'user'      => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'rumah'     => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'kartu'     => '<rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>',
        'grafik'    => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
        'keluar'    => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>',
        'cek'       => '<polyline points="20 6 9 17 4 12"/>',
        'cari'      => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
        'wifi'      => '<path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/>',
        'listrik'   => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
        'cctv'      => '<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/>',
        'perisai'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'lokasi'    => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
        'dapur'     => '<path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>',
        'parkir'    => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 17V7h4a3 3 0 0 1 0 6H9"/>',
        'laundry'   => '<rect x="4" y="2" width="16" height="20" rx="2"/><circle cx="12" cy="14" r="4"/><line x1="8" y1="6" x2="8.01" y2="6"/>',
        'peringatan'=> '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        'info'      => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
        'kasur'     => '<path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><line x1="2" y1="17" x2="22" y2="17"/>',
        'pintu'     => '<path d="M4 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17"/><line x1="2" y1="21" x2="22" y2="21"/><line x1="13" y1="12" x2="13.01" y2="12"/>',
        'panah'     => '<line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>',
        'unggah'    => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>',
    ];
    return '<svg class="ikon" width="' . $ukuran . '" height="' . $ukuran . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($p[$nama] ?? '') . '</svg>';
}

function logo_html(): string
{
    return 'SIPENK' . ikon('rumah', 17) . 'S';
}


function tab_filter(array $tabs, string $aktif, string $param = 'status', string $halaman = ''): string
{
    $out = '<div class="tabs">';
    foreach ($tabs as $nilai => $label) {
        $q = $_GET;
        unset($q[$param]);
        if ((string) $nilai !== '') {
            $q[$param] = $nilai;
        }
        $url = $halaman . ($q ? '?' . http_build_query($q) : '');
        $out .= '<a class="tab' . ((string) $nilai === $aktif ? ' aktif' : '') . '" href="' . e($url === '' ? '?' : $url) . '">' . e($label) . '</a>';
    }
    return $out . '</div>';
}

/** Indikator langkah reservasi. $aktif 1..3; $akhir = label langkah 3; $kelas3 = '', 'selesai', 'gagal'. */
function stepper(int $aktif, string $akhir = 'Menunggu Verifikasi', string $kelas3 = ''): string
{
    $langkah = [1 => 'Formulir Reservasi', 2 => 'Pembayaran', 3 => $akhir];
    $out = '<div class="stepper">';
    foreach ($langkah as $n => $label) {
        $k = $n < $aktif ? 'selesai' : ($n === $aktif ? 'aktif' : '');
        if ($n === 3 && $kelas3 !== '') {
            $k = $kelas3;
        }
        $nomor = $k === 'selesai' ? ikon('cek', 14) : ($k === 'gagal' ? '!' : $n);
        $out .= '<span class="langkah ' . $k . '"><span class="nomor">' . $nomor . '</span>' . e($label) . '</span>';
    }
    return $out . '</div>';
}

function mask_nik($nik): string
{
    $nik = (string) $nik;
    if (strlen($nik) !== 16) {
        return $nik === '' ? '-' : $nik;
    }
    return substr($nik, 0, 4) . str_repeat('•', 8) . substr($nik, -4);
}
