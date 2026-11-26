-- Untuk database yang SUDAH dibuat dengan versi lama (NIK wajib saat registrasi).
-- Jalankan sekali: psql -d sipenkos -f sql/migrasi_dari_versi_lama.sql
-- Aman diulang (idempotent). Menyamakan skema dengan 01/03/06 versi terbaru.
ALTER TABLE penghuni ALTER COLUMN nik DROP NOT NULL;

-- Rapikan data lama sebelum indeks unik dibuat: jika ada penghuni dengan >1 pemesanan
-- 'menunggu_verifikasi', pertahankan yang terbaru dan tandai sisanya 'kedaluwarsa'.
UPDATE pemesanan p SET status_pesanan = 'kedaluwarsa'
 WHERE status_pesanan = 'menunggu_verifikasi'
   AND id_pemesanan <> (SELECT MAX(x.id_pemesanan) FROM pemesanan x
                         WHERE x.id_penghuni = p.id_penghuni AND x.status_pesanan = 'menunggu_verifikasi');

CREATE UNIQUE INDEX IF NOT EXISTS uq_pemesanan_hold_kamar
    ON pemesanan (id_kamar) WHERE status_pesanan = 'menunggu_verifikasi' AND batas_hold IS NOT NULL;
CREATE UNIQUE INDEX IF NOT EXISTS uq_pemesanan_pending_penghuni
    ON pemesanan (id_penghuni) WHERE status_pesanan = 'menunggu_verifikasi';
CREATE UNIQUE INDEX IF NOT EXISTS uq_pembayaran_menunggu
    ON pembayaran (id_pemesanan) WHERE status_bayar = 'menunggu';

CREATE TABLE IF NOT EXISTS percobaan_login (
    id_percobaan SERIAL PRIMARY KEY,
    kunci        VARCHAR(150) NOT NULL,
    waktu        TIMESTAMP    NOT NULL DEFAULT NOW()
);
CREATE INDEX IF NOT EXISTS idx_percobaan_kunci_waktu ON percobaan_login (kunci, waktu);
