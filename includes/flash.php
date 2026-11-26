<?php
function set_flash(string $tipe, $pesan): void
{
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => (array) $pesan];
}

function tampil_flash(): void
{
    if (empty($_SESSION['flash'])) {
        return;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    echo '<div class="alert alert-' . e($f['tipe']) . '"><ul>';
    foreach ($f['pesan'] as $p) {
        echo '<li>' . e($p) . '</li>';
    }
    echo '</ul></div>';
}
