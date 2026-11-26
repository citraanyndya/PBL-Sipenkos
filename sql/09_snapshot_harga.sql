-- SIPENKOS | Snapshot harga & jejak verifikasi (aman dijalankan berulang). Jalankan SETELAH 01-08.
-- Ini PERUBAHAN dari PDM -> perbarui gambar PDM:
--   pemesanan : + harga_sewa_saat_pesan (harga per bulan yang DISEPAKATI saat pesan; tidak ikut berubah bila harga kamar diubah)
--   pembayaran: + tgl_verifikasi (kapan uang diterima/ditolak pemilik; dasar laporan keuangan)
--   riwayat_harga_kamar: tabel baru, jejak setiap perubahan harga kamar (audit)

-- 1) Harga kontrak per pemesanan ------------------------------------------------
ALTER TABLE pemesanan ADD COLUMN IF NOT EXISTS harga_sewa_saat_pesan NUMERIC(12,2);
UPDATE pemesanan SET harga_sewa_saat_pesan = ROUND(total_tagihan / durasi_bulan, 2) WHERE harga_sewa_saat_pesan IS NULL;
ALTER TABLE pemesanan ALTER COLUMN harga_sewa_saat_pesan SET NOT NULL;
ALTER TABLE pemesanan DROP CONSTRAINT IF EXISTS ck_pemesanan_harga;
ALTER TABLE pemesanan ADD  CONSTRAINT ck_pemesanan_harga CHECK (harga_sewa_saat_pesan > 0);
-- total harus selalu = harga kontrak x durasi (NOT VALID: baris lama tidak diperiksa ulang, baris baru wajib).
ALTER TABLE pemesanan DROP CONSTRAINT IF EXISTS ck_pemesanan_total;
ALTER TABLE pemesanan ADD  CONSTRAINT ck_pemesanan_total CHECK (total_tagihan = harga_sewa_saat_pesan * durasi_bulan) NOT VALID;

-- 2) Waktu verifikasi pembayaran -------------------------------------------------
ALTER TABLE pembayaran ADD COLUMN IF NOT EXISTS tgl_verifikasi TIMESTAMP(0);
-- Data lama: waktu verifikasi tidak pernah dicatat, pakai tgl_bayar sebagai perkiraan terbaik.
UPDATE pembayaran SET tgl_verifikasi = tgl_bayar WHERE status_bayar <> 'menunggu' AND tgl_verifikasi IS NULL;
ALTER TABLE pembayaran DROP CONSTRAINT IF EXISTS ck_pembayaran_verifikasi;
ALTER TABLE pembayaran ADD  CONSTRAINT ck_pembayaran_verifikasi CHECK (
       (status_bayar = 'menunggu' AND tgl_verifikasi IS NULL)
    OR (status_bayar <> 'menunggu' AND tgl_verifikasi IS NOT NULL));
CREATE INDEX IF NOT EXISTS idx_pembayaran_status_verif ON pembayaran (status_bayar, tgl_verifikasi);

-- 3) Jejak perubahan harga kamar -------------------------------------------------
CREATE TABLE IF NOT EXISTS riwayat_harga_kamar (
    id_riwayat   SERIAL PRIMARY KEY,
    id_kamar     INTEGER       NOT NULL REFERENCES kamar(id_kamar) ON DELETE CASCADE,
    harga_lama   NUMERIC(12,2) NOT NULL,
    harga_baru   NUMERIC(12,2) NOT NULL,
    tgl_ubah     TIMESTAMP(0)  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_pengelola INTEGER       REFERENCES pengelola(id_pengelola) ON DELETE SET NULL,
    CONSTRAINT ck_riwayat_harga CHECK (harga_lama > 0 AND harga_baru > 0 AND harga_lama <> harga_baru)
);
CREATE INDEX IF NOT EXISTS idx_riwayat_harga_kamar ON riwayat_harga_kamar (id_kamar, tgl_ubah);
