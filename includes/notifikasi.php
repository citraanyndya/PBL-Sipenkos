<?php

function ambil_notifikasi(PDO $pdo, string $role, int $id): array
{
    $n = [];
    if ($role === 'pengelola') {
        foreach ($pdo->query(
            "SELECT p.id_pemesanan, p.tgl_pemesanan AS waktu, g.nama_lengkap, k.kelas_kamar
               FROM pemesanan p JOIN penghuni g ON g.id_penghuni = p.id_penghuni JOIN kamar k ON k.id_kamar = p.id_kamar
              WHERE p.status_pesanan = 'menunggu_verifikasi' ORDER BY p.tgl_pemesanan DESC LIMIT 15"
        ) as $r) {
            $n[] = ['waktu' => $r['waktu'], 'judul' => $r['nama_lengkap'] . ' mengajukan reservasi ' . $r['kelas_kamar'],
                    'detail' => 'Menunggu keputusan pemilik', 'jenis' => 'reservasi', 'link' => 'admin/pemesanan/detail.php?id=' . $r['id_pemesanan']];
        }
        foreach ($pdo->query(
            "SELECT p.id_pemesanan, b.tgl_bayar AS waktu, g.nama_lengkap, b.jml_bayar
               FROM pembayaran b JOIN pemesanan p ON p.id_pemesanan = b.id_pemesanan JOIN penghuni g ON g.id_penghuni = p.id_penghuni
              WHERE b.status_bayar = 'menunggu' ORDER BY b.tgl_bayar DESC LIMIT 15"
        ) as $r) {
            $n[] = ['waktu' => $r['waktu'], 'judul' => $r['nama_lengkap'] . ' mengunggah bukti transfer',
                    'detail' => rupiah($r['jml_bayar']) . ' · menunggu verifikasi', 'jenis' => 'pembayaran', 'link' => 'admin/pemesanan/detail.php?id=' . $r['id_pemesanan']];
        }
        foreach ($pdo->query(
            "SELECT l.id_laporan, l.tgl_laporan AS waktu, l.deskripsi_laporan, g.nama_lengkap, k.nomor_kamar
               FROM laporan_kerusakan l JOIN penghuni g ON g.id_penghuni = l.id_penghuni JOIN kamar k ON k.id_kamar = l.id_kamar
              WHERE l.status_laporan = 'baru' ORDER BY l.tgl_laporan DESC LIMIT 15"
        ) as $r) {
            $n[] = ['waktu' => $r['waktu'], 'judul' => $r['nama_lengkap'] . ' melaporkan kerusakan (Kamar ' . $r['nomor_kamar'] . ')',
                    'detail' => mb_strimwidth($r['deskripsi_laporan'], 0, 70, '...'), 'jenis' => 'kerusakan', 'link' => 'admin/laporan/detail.php?id=' . $r['id_laporan']];
        }
    } else {
        $s = $pdo->prepare(
            "SELECT p.id_pemesanan, p.tgl_pemesanan, p.status_pesanan, p.batas_hold, k.kelas_kamar, " . sql_selesai('p') . " AS tgl_selesai,
                    (SELECT COUNT(*) FROM pembayaran b WHERE b.id_pemesanan = p.id_pemesanan AND b.status_bayar = 'menunggu') AS menunggu
               FROM pemesanan p JOIN kamar k ON k.id_kamar = p.id_kamar WHERE p.id_penghuni = :u ORDER BY p.id_pemesanan DESC LIMIT 10"
        );
        $s->execute(['u' => $id]);
        foreach ($s as $r) {
            $link = 'calon-penghuni/status-reservasi.php?id=' . $r['id_pemesanan'];
            if ($r['status_pesanan'] === 'diterima') {
                $n[] = ['waktu' => $r['tgl_pemesanan'], 'judul' => 'Reservasi ' . $r['kelas_kamar'] . ' diterima', 'detail' => 'Kamar siap ditempati sesuai tanggal mulai', 'jenis' => 'sukses', 'link' => $link];
                $sisa = (int) ((strtotime($r['tgl_selesai']) - strtotime(date('Y-m-d'))) / 86400);
                if ($sisa >= 0 && $sisa <= 30) {
                    $n[] = ['waktu' => date('Y-m-d H:i:s'), 'judul' => 'Masa sewa berakhir ' . $sisa . ' hari lagi', 'detail' => 'Berakhir ' . tgl_indo($r['tgl_selesai']) . ' · ajukan perpanjangan', 'jenis' => 'pengingat', 'link' => 'penghuni/perpanjangan-sewa.php'];
                }
            } elseif ($r['status_pesanan'] === 'ditolak') {
                $n[] = ['waktu' => $r['tgl_pemesanan'], 'judul' => 'Reservasi ' . $r['kelas_kamar'] . ' ditolak', 'detail' => 'Lihat alasan di halaman status', 'jenis' => 'ditolak', 'link' => $link];
            } elseif ($r['status_pesanan'] === 'kedaluwarsa') {
                $n[] = ['waktu' => $r['batas_hold'] ?: $r['tgl_pemesanan'], 'judul' => 'Reservasi dibatalkan: batas pembayaran terlewat', 'detail' => 'Kamar dilepas, silakan reservasi ulang', 'jenis' => 'ditolak', 'link' => $link];
            } elseif ($r['status_pesanan'] === 'menunggu_verifikasi' && (int) $r['menunggu'] === 0) {
                $n[] = ['waktu' => $r['tgl_pemesanan'], 'judul' => 'Segera bayar: kamar ditahan sampai ' . tgl_indo($r['batas_hold'], true), 'detail' => 'Unggah bukti transfer untuk ' . $r['kelas_kamar'], 'jenis' => 'pengingat', 'link' => 'calon-penghuni/pembayaran.php?id=' . $r['id_pemesanan']];
            }
        }
        $s = $pdo->prepare(
            "SELECT p.id_pemesanan, b.tgl_bayar, b.status_bayar, b.jml_bayar, b.alasan_penolakan
               FROM pembayaran b JOIN pemesanan p ON p.id_pemesanan = b.id_pemesanan
              WHERE p.id_penghuni = :u AND b.status_bayar IN ('diterima', 'ditolak') ORDER BY b.id_pembayaran DESC LIMIT 10"
        );
        $s->execute(['u' => $id]);
        foreach ($s as $r) {
            $ok = $r['status_bayar'] === 'diterima';
            $n[] = ['waktu' => $r['tgl_bayar'], 'judul' => $ok ? 'Pembayaran dikonfirmasi' : 'Bukti pembayaran ditolak',
                    'detail' => $ok ? rupiah($r['jml_bayar']) : (string) $r['alasan_penolakan'], 'jenis' => $ok ? 'sukses' : 'ditolak', 'link' => 'calon-penghuni/status-reservasi.php?id=' . $r['id_pemesanan']];
        }
        $s = $pdo->prepare("SELECT id_laporan, tgl_laporan, deskripsi_laporan, status_laporan FROM laporan_kerusakan WHERE id_penghuni = :u ORDER BY id_laporan DESC LIMIT 10");
        $s->execute(['u' => $id]);
        foreach ($s as $r) {
            $n[] = ['waktu' => $r['tgl_laporan'], 'judul' => 'Laporan kerusakan ' . ($r['status_laporan'] === 'selesai' ? 'selesai ditangani' : ($r['status_laporan'] === 'diproses' ? 'sedang diproses' : 'terkirim')),
                    'detail' => mb_strimwidth($r['deskripsi_laporan'], 0, 70, '...'), 'jenis' => 'kerusakan', 'link' => 'penghuni/laporan-kerusakan.php?id=' . $r['id_laporan']];
        }
    }
    usort($n, fn($a, $b) => strtotime($b['waktu']) <=> strtotime($a['waktu']));
    $dibaca = (int) ($_SESSION['notif_dibaca'] ?? 0);
    foreach ($n as &$item) {
        $item['baru'] = strtotime($item['waktu']) > $dibaca;
    }
    return array_slice($n, 0, 30);
}

function jumlah_notifikasi_baru(array $notif): int
{
    return count(array_filter($notif, fn($x) => $x['baru']));
}
