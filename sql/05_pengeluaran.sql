-- Tahap 5 (Prioritas 2): pengeluaran operasional untuk kalkulasi pendapatan bersih
-- Jalankan: psql -d sipenkos -f sql/05_pengeluaran.sql

CREATE TABLE IF NOT EXISTS pengeluaran_operasional (
    id_pengeluaran        SERIAL PRIMARY KEY,
    kategori              VARCHAR(50)   NOT NULL,
    deskripsi_pengeluaran TEXT          NOT NULL,
    jml_biaya             NUMERIC(12,2) NOT NULL CHECK (jml_biaya > 0),
    tgl_pengeluaran       DATE          NOT NULL,
    id_pengelola          INTEGER NOT NULL REFERENCES pengelola(id_pengelola)
);
