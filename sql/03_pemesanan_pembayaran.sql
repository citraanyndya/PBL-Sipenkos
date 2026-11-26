-- Tahap 3: pemesanan (tagihan digabung di total_tagihan) dan pembayaran
-- Jalankan: psql -d sipenkos -f sql/03_pemesanan_pembayaran.sql

-- batas_hold NULL untuk perpanjangan sewa (kamar sudah terisi, tidak perlu di-hold).
CREATE TABLE IF NOT EXISTS pemesanan (
    id_pemesanan   SERIAL PRIMARY KEY,
    tgl_pemesanan  TIMESTAMP     NOT NULL DEFAULT NOW(),
    tgl_mulai      DATE          NOT NULL,
    durasi_bulan   INTEGER       NOT NULL CHECK (durasi_bulan IN (1, 6, 12)),
    total_tagihan  NUMERIC(12,2) NOT NULL,
    status_pesanan VARCHAR(30)   NOT NULL DEFAULT 'menunggu_verifikasi'
                   CHECK (status_pesanan IN ('menunggu_verifikasi', 'diterima', 'ditolak', 'kedaluwarsa')),
    batas_hold     TIMESTAMP,
    id_penghuni    INTEGER NOT NULL REFERENCES penghuni(id_penghuni),
    id_kamar       INTEGER NOT NULL REFERENCES kamar(id_kamar),
    id_pengelola_verifikator INTEGER REFERENCES pengelola(id_pengelola) ON DELETE SET NULL
);
CREATE INDEX IF NOT EXISTS idx_pemesanan_status_hold ON pemesanan (status_pesanan, batas_hold);

CREATE TABLE IF NOT EXISTS pembayaran (
    id_pembayaran    SERIAL PRIMARY KEY,
    tgl_bayar        TIMESTAMP     NOT NULL DEFAULT NOW(),
    jml_bayar        NUMERIC(12,2) NOT NULL,
    bukti_transfer   VARCHAR(255)  NOT NULL,
    status_bayar     VARCHAR(20)   NOT NULL DEFAULT 'menunggu'
                     CHECK (status_bayar IN ('menunggu', 'diterima', 'ditolak')),
    alasan_penolakan TEXT,
    id_pemesanan     INTEGER NOT NULL REFERENCES pemesanan(id_pemesanan),
    id_pengelola_verifikator INTEGER REFERENCES pengelola(id_pengelola) ON DELETE SET NULL
);

-- ---------------------------------------------------------------------------
-- Jaring pengaman tingkat DATABASE (race condition).
-- Aplikasi sudah memakai transaksi + penguncian baris; indeks unik parsial ini
-- menjamin aturan bisnis tetap utuh walau ada bug/jalur akses lain:
--   1) satu kamar hanya boleh punya SATU reservasi baru yang sedang menunggu (hold),
--   2) satu penghuni hanya boleh punya SATU pemesanan yang sedang menunggu,
--   3) satu pemesanan hanya boleh punya SATU bukti bayar berstatus 'menunggu'.
-- ---------------------------------------------------------------------------
CREATE UNIQUE INDEX IF NOT EXISTS uq_pemesanan_hold_kamar
    ON pemesanan (id_kamar) WHERE status_pesanan = 'menunggu_verifikasi' AND batas_hold IS NOT NULL;
CREATE UNIQUE INDEX IF NOT EXISTS uq_pemesanan_pending_penghuni
    ON pemesanan (id_penghuni) WHERE status_pesanan = 'menunggu_verifikasi';
CREATE UNIQUE INDEX IF NOT EXISTS uq_pembayaran_menunggu
    ON pembayaran (id_pemesanan) WHERE status_bayar = 'menunggu';
