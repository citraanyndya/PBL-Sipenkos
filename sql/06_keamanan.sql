-- Tahap 6: pembatas percobaan login/registrasi (anti brute-force). Bukan bagian PDM inti.
-- Jalankan: psql -d sipenkos -f sql/06_keamanan.sql
CREATE TABLE IF NOT EXISTS percobaan_login (
    id_percobaan SERIAL PRIMARY KEY,
    kunci        VARCHAR(150) NOT NULL,
    waktu        TIMESTAMP    NOT NULL DEFAULT NOW()
);
CREATE INDEX IF NOT EXISTS idx_percobaan_kunci_waktu ON percobaan_login (kunci, waktu);
