-- Tahap 4: laporan kerusakan fasilitas dan aturan kos
-- Jalankan: psql -d sipenkos -f sql/04_laporan_aturan.sql

CREATE TABLE IF NOT EXISTS laporan_kerusakan (
    id_laporan        SERIAL PRIMARY KEY,
    tgl_laporan       TIMESTAMP    NOT NULL DEFAULT NOW(),
    deskripsi_laporan TEXT         NOT NULL,
    foto_kerusakan    VARCHAR(255),
    status_laporan    VARCHAR(30)  NOT NULL DEFAULT 'baru'
                      CHECK (status_laporan IN ('baru', 'diproses', 'selesai')),
    id_penghuni       INTEGER NOT NULL REFERENCES penghuni(id_penghuni),
    id_kamar          INTEGER NOT NULL REFERENCES kamar(id_kamar),
    id_pengelola      INTEGER REFERENCES pengelola(id_pengelola) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS aturan_kos (
    id_aturan        SERIAL PRIMARY KEY,
    nama_aturan      VARCHAR(100) NOT NULL,
    deskripsi_aturan TEXT         NOT NULL,
    id_pengelola     INTEGER NOT NULL REFERENCES pengelola(id_pengelola)
);
