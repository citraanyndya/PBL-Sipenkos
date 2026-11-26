<?php
/**
 * Kerangka tampilan frontend tim (calon-penghuni, penghuni, auth) - HTML/CSS asli tim, data dari backend.
 * Wajib di-include SETELAH bootstrap.php dan koneksi.php. Semua halaman berada 1 folder di bawah root ($base = '../').
 */
require_once __DIR__ . '/notifikasi.php';

const FE_FONT = 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap';

/** Penghuni punya sewa aktif (diterima & belum berakhir)? Menentukan menu "state-based". */
function fe_penghuni_aktif(PDO $pdo): bool
{
    if (($_SESSION['role'] ?? '') !== 'penghuni') {
        return false;
    }
    $s = $pdo->prepare("SELECT 1 FROM pemesanan p WHERE p.id_penghuni = :u AND p.status_pesanan = 'diterima' AND " . sql_selesai('p') . " >= CURRENT_DATE LIMIT 1");
    $s->execute(['u' => $_SESSION['user_id']]);
    return (bool) $s->fetchColumn();
}

/** Titik merah notifikasi: ada notifikasi yang belum dibaca. */
function fe_notif_baru(PDO $pdo): bool
{
    if (empty($_SESSION['user_id'])) {
        return false;
    }
    return jumlah_notifikasi_baru(ambil_notifikasi($pdo, $_SESSION['role'], (int) $_SESSION['user_id'])) > 0;
}

/** Alert flash (pesan sekali tampil) dengan gaya tim. */
function fe_flash(): void
{
    if (empty($_SESSION['flash'])) {
        return;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $tipe = in_array($f['tipe'], ['sukses', 'error', 'info'], true) ? $f['tipe'] : 'info';
    echo '<div class="flash flash-' . $tipe . '" role="alert">';
    foreach ((array) $f['pesan'] as $p) {
        echo '<p>' . e($p) . '</p>';
    }
    echo '</div>';
}

function fe_head(string $judul, string $folderCss, array $css): void
{
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . e($judul) . ' - SIPENKOS</title>';
    echo '<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link href="' . FE_FONT . '" rel="stylesheet">';
    foreach (array_merge(['style'], $css) as $c) {
        echo '<link rel="stylesheet" href="../assets/css/' . $folderCss . '/' . e($c) . '.css">';
    }
    echo '<link rel="stylesheet" href="../assets/css/integrasi.css">';
    echo '</head>';
}

/** Header + pembuka halaman publik/calon penghuni. $aktif: beranda|kamar|tentang */
function fe_awal_calon(PDO $pdo, string $judul, array $css = [], string $aktif = ''): void
{
    fe_head($judul, 'calon-penghuni', $css);
    $masuk = ($_SESSION['role'] ?? '') === 'penghuni';
    $notif = $masuk && fe_penghuni_aktif($pdo) ? '../penghuni/notifikasi.php' : 'notifikasi.php';
    $m = fn($k) => $aktif === $k ? 'nav active' : 'nav';
    echo '<body><header class="navbar"><div class="container navbar-container">';
    echo '<a href="beranda.php" class="logo"><span class="logo-judul">SIPENKOS</span><span class="logo-divider">|</span><span class="logo-pendukung">Kos Bu Nining</span></a>';
    echo '<nav class="menu"><a href="beranda.php" class="' . $m('beranda') . '">Beranda</a><a href="daftar-kamar.php" class="' . $m('kamar') . '">Kamar</a><a href="tentang.php" class="' . $m('tentang') . '">Tentang</a></nav>';
    echo '<div class="nav-actions-wrapper">';
    if ($masuk) {
        $nama = $_SESSION['nama'] ?? '';
        echo '<div class="user-profile" id="userProfile"><a class="btn-icon-notif" href="' . $notif . '" title="Notifikasi" aria-label="Notifikasi">🔔' . (fe_notif_baru($pdo) ? '<span class="badge-dot"></span>' : '') . '</a>';
        echo '<a class="user-info" href="../penghuni/index.php" title="Ke dasbor"><div class="user-avatar">' . e(inisial($nama)) . '</div><span class="user-name">' . e($nama) . '</span></a>';
        echo '<a class="btn btn-outline btn-logout" id="logoutButton" data-logout href="../auth/logout.php">Keluar</a></div>';
    } elseif (($_SESSION['role'] ?? '') === 'pengelola') {
        echo '<div class="nav-actions"><a href="../admin/index.php" class="btn btn-primary">Dasbor Admin</a></div>';
    } else {
        echo '<div class="nav-actions" id="guestActions"><a href="registrasi.php" class="btn btn-outline">Daftar</a><a href="login.php" class="btn btn-primary">Masuk</a></div>';
    }
    echo '</div></div></header>';
}

function fe_akhir_calon(array $js = []): void
{
    echo '<footer class="footer"><div class="logo-judul text-white">SIPENKOS</div><p class="footer-tagline">🛡️ Hunian terpercaya &amp; dikelola langsung oleh pemilik</p>';
    echo '<p class="footer-location">Kos Bu Nining · Malang, Jawa Timur</p><div class="kontak"><span>📍 Jl. Pisang Agung III No. 29</span><span>💬 WhatsApp 0854-4421-1076</span><span>⏰ Jam bertamu 07.00–22.00 WIB</span></div>';
    echo '<div class="copyright">© 2026 SIPENKOS - Kos Bu Nining Malang</div></footer>';
    echo '<script src="../assets/js/umum.js"></script>';
    foreach ($js as $j) {
        echo '<script src="../assets/js/' . e($j) . '.js"></script>';
    }
    echo '</body></html>';
}

/** Halaman auth (login/registrasi): tanpa navbar. */
function fe_awal_auth(string $judul, array $css = []): void
{
    fe_head($judul, 'calon-penghuni', $css);
    echo '<body>';
}

/** Kerangka area penghuni (sidebar + topbar). $aktif: dashboard|tagihan|riwayat|perpanjangan|laporan|aturan */
function fe_awal_penghuni(PDO $pdo, string $judul, array $css = [], string $aktif = ''): void
{
    fe_head($judul, 'penghuni', $css);
    $menu = ['dashboard' => ['dashboard.php', '▦', 'Dashboard'], 'tagihan' => ['tagihan.php', '▤', 'Tagihan'], 'riwayat' => ['riwayat-pembayaran.php', '◴', 'Riwayat Pembayaran'],
             'perpanjangan' => ['perpanjangan-sewa.php', '▣', 'Perpanjangan Sewa'], 'laporan' => ['laporan-kerusakan.php', '⚒', 'Laporan Kerusakan'], 'aturan' => ['aturan.php', '⚑', 'Kebijakan &amp; Aturan Kos']];
    $nama = $_SESSION['nama'] ?? '';
    echo '<body><div class="dashboard-layout"><aside class="sidebar" id="sidebar"><nav class="sidebar-nav" aria-label="Navigasi utama">';
    foreach ($menu as $k => [$url, $ik, $lbl]) {
        echo '<a class="nav-link' . ($aktif === $k ? ' active' : '') . '" href="' . $url . '"><span class="nav-icon">' . $ik . '</span>' . $lbl . '</a>';
    }
    echo '</nav><div class="sidebar-bottom"><a class="logout-button" id="logoutButton" href="../auth/logout.php"><span class="nav-icon">⇥</span>Keluar</a></div></aside>';
    echo '<main class="main-area"><header class="navbar"><div class="container navbar-container"><a href="dashboard.php" class="logo"><span class="logo-judul">SIPENKOS</span><span class="logo-divider">|</span><span class="logo-pendukung">Kos Bu Nining</span></a></div>';
    echo '<div class="user-profile" id="userProfile"><a class="btn-icon-notif" href="notifikasi.php" title="Notifikasi" aria-label="Notifikasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>' . (fe_notif_baru($pdo) ? '<span class="badge-dot" id="notificationDot"></span>' : '') . '</a>';
    echo '<span class="header-divider" aria-hidden="true"></span><div class="account"><span class="user-avatar">' . e(inisial($nama)) . '</span><span class="user-name">' . e($nama) . '</span></div></div></header>';
    echo '<section class="container">';
    fe_flash();
}

function fe_akhir_penghuni(array $js = []): void
{
    echo '</section></main></div><div class="sidebar-overlay" id="sidebarOverlay"></div>';
    echo '<script src="../assets/js/umum.js"></script>';
    foreach ($js as $j) {
        echo '<script src="../assets/js/' . e($j) . '.js"></script>';
    }
    echo '</body></html>';
}

/** Foto kamar: unggahan admin bila ada, selain itu foto bawaan tim per tipe. */
function fe_foto_kamar(?string $foto, string $kelas): string
{
    if ($foto) {
        return url_foto_kamar($foto);
    }
    $n = preg_match('/^Tipe (\d)/', $kelas, $m) ? (int) $m[1] : 1;
    return '../assets/images/kamar-tipe' . min(max($n, 1), 4) . '.jpeg';
}

/** Badge "Tersedia · sisa n" / "Penuh" gaya tim. */
function fe_badge_sisa(int $sisa): string
{
    return $sisa > 0 ? '<span class="tersedia">Tersedia - sisa ' . $sisa . '</span>' : '<span class="penuh">Penuh</span>';
}

/** Ringkasan per tipe kamar (kartu beranda/daftar). $filter: ''|ac|non-ac|km-dalam|km-luar. */
function fe_daftar_tipe(PDO $pdo, string $filter = '', string $kata = ''): array
{
    $kondisi = ['ac' => "kelas_kamar NOT ILIKE '%Non-AC%'", 'non-ac' => "kelas_kamar ILIKE '%Non-AC%'", 'km-dalam' => "kelas_kamar ILIKE '%Mandi Dalam%'", 'km-luar' => "kelas_kamar ILIKE '%Mandi Luar%'"];
    $where = [];
    $par = [];
    if ($kata !== '') {
        $where[] = 'kelas_kamar ILIKE :kw';
        $par['kw'] = '%' . $kata . '%';
    }
    if (isset($kondisi[$filter])) {
        $where[] = $kondisi[$filter];
    }
    $st = $pdo->prepare("SELECT kelas_kamar, COALESCE(MIN(harga_sewa) FILTER (WHERE status_kamar = 'tersedia'), MIN(harga_sewa)) AS harga, COUNT(*) AS total, COUNT(*) FILTER (WHERE status_kamar = 'tersedia') AS sisa, MAX(foto_kamar) AS foto
                           FROM kamar " . ($where ? 'WHERE ' . implode(' AND ', $where) : '') . ' GROUP BY kelas_kamar ORDER BY MIN(harga_sewa)');
    $st->execute($par);
    return $st->fetchAll();
}

/** Deskripsi singkat kartu: kalimat pertama deskripsi tipe, atau fallback. */
function fe_deskripsi_singkat(string $kelas): string
{
    require_once __DIR__ . '/fe_data.php';
    $n = preg_match('/^Tipe (\d)/', $kelas, $m) ? (int) $m[1] : 0;
    $d = FE_TIPE[$n]['deskripsi'] ?? 'Kamar nyaman di Kos Bu Nining.';
    return preg_match('/^(.{40,160}?[.])\s/u', $d, $x) ? $x[1] : $d;
}

/** Banner status reservasi untuk calon penghuni di beranda (null bila tidak perlu). */
function fe_banner_reservasi(PDO $pdo): ?array
{
    if (($_SESSION['role'] ?? '') !== 'penghuni') {
        return null;
    }
    $s = $pdo->prepare("SELECT p.id_pemesanan, p.status_pesanan, (SELECT COUNT(*) FROM pembayaran b WHERE b.id_pemesanan = p.id_pemesanan AND b.status_bayar = 'menunggu') AS menunggu
                          FROM pemesanan p WHERE p.id_penghuni = :u ORDER BY p.id_pemesanan DESC LIMIT 1");
    $s->execute(['u' => $_SESSION['user_id']]);
    $p = $s->fetch();
    if (!$p) {
        return null;
    }
    $id = (int) $p['id_pemesanan'];
    $st = 'status-reservasi.php?id=' . $id;
    if ($p['status_pesanan'] === 'menunggu_verifikasi') {
        return (int) $p['menunggu'] > 0
            ? ['⏳', 'Reservasimu sedang diverifikasi', 'Bukti pembayaran sedang diperiksa oleh pemilik kos.', $st, 'Lihat Status Reservasi']
            : ['💳', 'Selesaikan pembayaran', 'Kamarmu ditahan sementara. Unggah bukti transfer agar reservasi diproses.', 'pembayaran.php?id=' . $id, 'Bayar Sekarang'];
    }
    return match ($p['status_pesanan']) {
        'diterima' => ['✅', 'Reservasimu diterima', 'Pembayaran sudah dikonfirmasi pemilik kos.', $st, 'Lihat Status Reservasi'],
        'ditolak' => ['❌', 'Reservasimu ditolak', 'Lihat alasan penolakan di halaman status.', $st, 'Lihat Status Reservasi'],
        'kedaluwarsa' => ['⚠️', 'Reservasi kedaluwarsa', 'Batas waktu pembayaran terlewat dan kamar sudah dilepas.', $st, 'Lihat Status Reservasi'],
        default => null,
    };
}

/** Satu kartu tipe kamar (markup tim). $detail: tampilkan harga + tombol detail (halaman daftar). */
function fe_kartu_kamar(array $t, bool $detail): string
{
    $url = 'detail-kamar.php?kelas=' . urlencode($t['kelas_kamar']);
    $h = '<article class="card-kamar"><div class="card"><img src="' . e(fe_foto_kamar($t['foto'], $t['kelas_kamar'])) . '" alt="' . e($t['kelas_kamar']) . '" loading="lazy">' . fe_badge_sisa((int) $t['sisa']) . '</div><div class="card-body">';
    $h .= '<h3 class="tipe-kamar">' . e($t['kelas_kamar']) . '</h3><p class="deskripsi-kamar">' . e(fe_deskripsi_singkat($t['kelas_kamar'])) . '</p>';
    if ($detail) {
        $h .= '<div class="harga-container"><span class="label">Biaya sewa</span><div class="harga' . ((int) $t['sisa'] > 0 ? '' : ' text-muted-harga') . '"><span class="nominal">' . e(rupiah($t['harga'])) . '</span> / bulan</div></div>';
        $h .= '<a href="' . $url . '" class="btn btn-outline btn-full">Lihat Detail</a>';
    }
    return $h . '</div></article>';
}

/** Sewa aktif penghuni yang sedang login (masa sewa gabungan termasuk perpanjangan). */
function fe_sewa_aktif(PDO $pdo): ?array
{
    $s = $pdo->prepare("SELECT k.id_kamar, k.nomor_kamar, k.kelas_kamar, k.harga_sewa, k.foto_kamar, MIN(p.tgl_mulai) AS mulai, MAX(" . sql_selesai('p') . ") AS selesai,
                                 (SELECT p2.harga_sewa_saat_pesan FROM pemesanan p2
                                   WHERE p2.id_penghuni = :u2 AND p2.id_kamar = k.id_kamar AND p2.status_pesanan = 'diterima'
                                   ORDER BY (p2.tgl_mulai <= CURRENT_DATE) DESC, p2.tgl_mulai DESC LIMIT 1) AS harga_kontrak
                          FROM pemesanan p JOIN kamar k ON k.id_kamar = p.id_kamar
                         WHERE p.id_penghuni = :u AND p.status_pesanan = 'diterima' AND " . sql_selesai('p') . " >= CURRENT_DATE
                         GROUP BY k.id_kamar ORDER BY MIN(p.tgl_mulai) LIMIT 1");
    $s->execute(['u' => $_SESSION['user_id'], 'u2' => $_SESSION['user_id']]);
    return $s->fetch() ?: null;
}

/** Pemesanan yang masih berjalan (menunggu bukti / verifikasi) milik penghuni, bila ada. */
function fe_pemesanan_berjalan(PDO $pdo): ?array
{
    $s = $pdo->prepare("SELECT p.*, k.nomor_kamar, k.kelas_kamar, (SELECT COUNT(*) FROM pembayaran b WHERE b.id_pemesanan = p.id_pemesanan AND b.status_bayar = 'menunggu') AS menunggu
                          FROM pemesanan p JOIN kamar k ON k.id_kamar = p.id_kamar WHERE p.id_penghuni = :u AND p.status_pesanan = 'menunggu_verifikasi' ORDER BY p.id_pemesanan DESC LIMIT 1");
    $s->execute(['u' => $_SESSION['user_id']]);
    return $s->fetch() ?: null;
}

/** Wajib penghuni dengan sewa aktif; bila belum, arahkan ke status reservasi/beranda. */
function fe_wajib_aktif(PDO $pdo): array
{
    $sewa = fe_sewa_aktif($pdo);
    if (!$sewa) {
        set_flash('info', 'Menu ini tersedia setelah reservasi kamu diterima pemilik kos.');
        redirect('../calon-penghuni/status-reservasi.php');
    }
    return $sewa;
}

/** Pecah deskripsi laporan "[Kategori] Judul\nIsi" menjadi [kategori, judul, isi]. */
function fe_pecah_laporan(string $d): array
{
    $baris = explode("\n", $d, 2);
    $kat = 'Lainnya';
    $judul = trim($baris[0]);
    if (preg_match('/^\[([^\]]+)\]\s*(.*)$/u', $judul, $m)) {
        $kat = $m[1];
        $judul = $m[2];
    }
    return [$kat, $judul !== '' ? $judul : 'Laporan kerusakan', trim($baris[1] ?? '')];
}

if (!function_exists('nama_bulan')) {
    /** '2026-11' -> 'November 2026' */
    function nama_bulan(string $ym): string
    {
        $b = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $t = strtotime($ym . '-01');
        return $b[(int) date('n', $t)] . ' ' . date('Y', $t);
    }
}
