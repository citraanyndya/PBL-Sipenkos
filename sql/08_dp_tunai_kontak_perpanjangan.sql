-- SIPENKOS | Perluasan skema (aman dijalankan berulang). Jalankan SETELAH 01-07.
-- Ini PERUBAHAN dari PDM awal -> perbarui gambar PDM:
--   penghuni  : + nama_kontak_darurat, + hubungan_kontak_darurat
--   pembayaran: + metode_bayar (transfer|tunai), + jenis_bayar (penuh|dp|pelunasan)
--               (kolom bukti_transfer tetap dipakai: berisi foto bukti transfer ATAU foto kwitansi bila tunai)
--   pemesanan : + id_pemesanan_asal (penanda perpanjangan; boleh pindah kamar/tipe)

-- 1) Kontak darurat: siapa yang dihubungi + hubungannya.
ALTER TABLE penghuni ADD COLUMN IF NOT EXISTS nama_kontak_darurat      VARCHAR(100);
ALTER TABLE penghuni ADD COLUMN IF NOT EXISTS hubungan_kontak_darurat  VARCHAR(30);

-- 2) Pembayaran: transfer / tunai dan penuh / DP / pelunasan.
ALTER TABLE pembayaran ADD COLUMN IF NOT EXISTS metode_bayar VARCHAR(10) NOT NULL DEFAULT 'transfer';
ALTER TABLE pembayaran ADD COLUMN IF NOT EXISTS jenis_bayar  VARCHAR(10) NOT NULL DEFAULT 'penuh';
ALTER TABLE pembayaran DROP CONSTRAINT IF EXISTS ck_pembayaran_metode;
ALTER TABLE pembayaran ADD  CONSTRAINT ck_pembayaran_metode CHECK (metode_bayar IN ('transfer', 'tunai'));
ALTER TABLE pembayaran DROP CONSTRAINT IF EXISTS ck_pembayaran_jenis;
ALTER TABLE pembayaran ADD  CONSTRAINT ck_pembayaran_jenis  CHECK (jenis_bayar IN ('penuh', 'dp', 'pelunasan'));

-- 3) Perpanjangan: menunjuk pemesanan sebelumnya (kamar boleh berbeda).
ALTER TABLE pemesanan ADD COLUMN IF NOT EXISTS id_pemesanan_asal INTEGER REFERENCES pemesanan(id_pemesanan);
CREATE INDEX IF NOT EXISTS idx_pemesanan_asal ON pemesanan(id_pemesanan_asal);
