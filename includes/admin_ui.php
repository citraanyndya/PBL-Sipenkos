<?php
// Helper tampilan panel admin (memakai komponen CSS tim: assets/css/admin/style.css).

/** Ikon ber-mask dari assets/icons (nama = ikon Lucide milik tim). */
function a_ikon(string $nama): string
{
    return '<i class="icon icon-' . e($nama) . '" aria-hidden="true"></i>';
}

/** Badge status gaya tim (titik + label). */
function a_badge(string $status, ?string $label = null): string
{
    $peta = [
        'tersedia' => ['success', 'Tersedia'], 'ditahan' => ['warning', 'Ditahan'], 'terisi' => ['info', 'Terisi'],
        'menunggu_verifikasi' => ['warning', 'Menunggu'], 'menunggu' => ['warning', 'Menunggu'],
        'diterima' => ['success', 'Diterima'], 'ditolak' => ['danger', 'Ditolak'], 'kedaluwarsa' => ['neutral', 'Kedaluwarsa'],
        'baru' => ['danger', 'Belum Dikerjakan'], 'diproses' => ['info', 'Diproses'], 'selesai' => ['success', 'Selesai'],
        'aktif' => ['success', 'Aktif'], 'calon' => ['info', 'Calon'], 'lunas' => ['success', 'Lunas'],
    ];
    [$tone, $teks] = $peta[$status] ?? ['neutral', $status];
    return '<span class="badge badge-' . $tone . '"><span class="badge-dot"></span><span class="badge-label">' . e($label ?? $teks) . '</span></span>';
}

/** Chip filter (tautan). $tabs: [nilai => label]. Parameter GET lain ikut dipertahankan. */
function a_chips(array $tabs, string $aktif, string $param = 'status'): string
{
    $out = '<div class="chips">';
    foreach ($tabs as $nilai => $label) {
        $q = $_GET;
        unset($q[$param]);
        if ((string) $nilai !== '') {
            $q[$param] = $nilai;
        }
        $url = $q ? '?' . http_build_query($q) : '?';
        $out .= '<a class="chip' . ((string) $nilai === $aktif ? ' is-active' : '') . '" href="' . e($url) . '">' . e($label) . '</a>';
    }
    return $out . '</div>';
}

/** Baris orang (avatar + nama + sub). */
function a_orang(string $nama, string $sub = ''): string
{
    return '<div class="person"><span class="avatar">' . e(inisial($nama)) . '</span><div><p class="person-name">' . e($nama) . '</p>'
        . ($sub !== '' ? '<p class="person-sub">' . e($sub) . '</p>' : '') . '</div></div>';
}

/** Flash message sebagai banner tim. */
function a_flash(): void
{
    if (empty($_SESSION['flash'])) {
        return;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $peta = ['sukses' => ['success', 'circle-check'], 'error' => ['danger', 'triangle-alert'], 'info' => ['info', 'info']];
    [$tone, $ik] = $peta[$f['tipe']] ?? $peta['info'];
    echo '<div class="banner banner-' . $tone . '" role="alert"><div class="banner-body">' . a_ikon($ik) . '<div>';
    foreach ((array) $f['pesan'] as $p) {
        echo '<div>' . e($p) . '</div>';
    }
    echo '</div></div></div>';
}
