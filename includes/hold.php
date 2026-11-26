<?php
// Melepas hold yang sudah habis. PHP native tidak punya proses latar belakang,
// jadi fungsi ini dipanggil dari header.php setiap halaman dibuka (satu query ringan).
//
// Pemesanan "menunggu_verifikasi" yang batas_hold-nya lewat DAN belum punya bukti
// transfer berstatus "menunggu" -> "kedaluwarsa", kamar kembali "tersedia".
// (Jika bukti sedang menunggu pemilik, hold dipertahankan sampai pemilik memutuskan.)
function lepas_hold_kedaluwarsa(PDO $pdo)
{
    $pdo->exec(
        "WITH habis AS (
             UPDATE pemesanan p SET status_pesanan = 'kedaluwarsa'
              WHERE p.status_pesanan = 'menunggu_verifikasi'
                AND p.batas_hold IS NOT NULL AND p.batas_hold < NOW()
                AND NOT EXISTS (SELECT 1 FROM pembayaran b
                                 WHERE b.id_pemesanan = p.id_pemesanan AND b.status_bayar = 'menunggu')
              RETURNING p.id_kamar
         )
         UPDATE kamar SET status_kamar = 'tersedia'
          WHERE id_kamar IN (SELECT id_kamar FROM habis) AND status_kamar = 'ditahan'"
    );
    // Kamar 'terisi' yang seluruh masa sewanya sudah berakhir (mis. penghuni pindah kamar saat perpanjangan) kembali 'tersedia'.
    $pdo->exec(
        "UPDATE kamar k SET status_kamar = 'tersedia'
          WHERE k.status_kamar = 'terisi'
            AND NOT EXISTS (SELECT 1 FROM pemesanan p
                             WHERE p.id_kamar = k.id_kamar AND p.status_pesanan = 'diterima'
                               AND " . sql_selesai('p') . " >= CURRENT_DATE)"
    );
}
